<?php

namespace app\models\tariff;

use app\models\addition\Addition;
use app\models\MessageToPaid;
use app\models\service\Service;
use app\models\TariffAddition;
use app\models\TariffAdditionQuantity;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tariff".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 * @property string $name
 * @property string $cost Стоимость обслуживания (ежемесячно)
 * @property string $about Описание
 * @property int $drop Запрещает подключать тариф хуже
 * @property int $status Статус
 * @property int $maximum Макимальный тариф
 * @property string $term Промо тариф
 *
 * @property TariffAddition[] $tariffAdditions
 * @property TariffAdditionQuantity[] $tariffAdditionsQty
 *
 * @property array $resolutionServiceQuantity[] Количество
 * @property array $connectedServiceQuantity[] Количество
 */
class Tariff extends ActiveRecord {

    public $resolutionService = [];
    public $resolutionServiceQuantity = [];
    public $connectedService = [];
    public $connectedServiceQuantity = [];

    const DROP_FALSE = 0;
    const DROP_TRUE = 1;

    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'tariff';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'cost'], 'required'],
            [['drop', 'status', 'maximum'], 'boolean', 'trueValue' => true, 'falseValue' => false],
            [['cost'], 'number'],
            [['about'], 'string'],
            [['term'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['resolutionService', 'connectedService'], 'each', 'rule' => ['integer']],
            [['resolutionServiceQuantity', 'connectedServiceQuantity'], 'each', 'rule' => ['integer', 'min' => 0,
                'max' => 999]],
            [['resolutionServiceQuantity', 'connectedServiceQuantity'], 'default', 'value' => 1],
        ];
    }

    /**
     * @return array
     */
    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'cost' => 'Стоимость',
            'about' => 'Описание',
            'drop' => 'Запрещает подключать тариф хуже',
            'status' => 'Статус',
            'maximum' => 'Макимальный тариф',
            'term' => 'Промо тариф',

            'resolutionService' => 'Доп. Услуги которые можно подключать',
            'resolutionServiceQuantity' => 'Количество',
            'connectedService' => 'Бесплатные услуги (стоимость входит в тариф)',
            'connectedServiceQuantity' => 'Количество',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddition() {
        return $this->hasMany(Addition::class, ['id' => 'addition_id'])->indexBy('id')
            ->via('tariffAddition');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTariffAddition() {
        return $this->hasMany(TariffAddition::class, ['tariff_id' => 'id'])->indexBy('addition_id');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionQty() {
        return $this->hasMany(Addition::class, ['id' => 'addition_id'])->indexBy('id')
            ->via('tariffAdditionQty');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTariffAdditionQty() {
        return $this->hasMany(TariffAdditionQuantity::class, ['tariff_id' => 'id'])->indexBy('addition_id');
    }

    /**
     * Получаем значение дропа
     *
     * @param string $i
     *
     * @return array|mixed
     */
    public static function getDrop($i = null) {
        $dropArray = [
            self::DROP_TRUE => 'Да',
            self::DROP_FALSE => 'Нет',
        ];

        return $i === null ? $dropArray : $dropArray[$i];
    }

    /**
     * Получаем значение статуса
     *
     * @param string $i
     *
     * @return array|mixed
     */
    public static function getStatus($i = null) {
        $statusArray = [
            self::STATUS_ON => 'Включен',
            self::STATUS_OFF => 'Выключен',
        ];

        return $i === null ? $statusArray : $statusArray[$i];
    }

    /**
     * Действия которые выполняются после сохранения
     *
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave ($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        if ($this->cost != $changedAttributes['cost']) {
            $all_message_service_id = [];
            $services = Service::find()->where(['type_service' => Service::TYPE_TARIFF])->andWhere(['OR',
                    'type_serviceId' => $this->id, 'old_service_id' => $this->id])->all();
            if (!empty($services)) {
                foreach ($services as $service) {
                    if ($service->type_serviceId == $this->id) {
                        $service->writeoff_amount = $this->cost;
                        $all_message_service_id[] = $service->id;
                    }

                    if ($service->old_service_id == $this->id) {
                        $service->old_writeoff_amount = $this->cost;
                    }
                    $service->save(false);
                }
            }

            $messages = MessageToPaid::find()->where(['service_id' => $all_message_service_id])->all();
            if (!empty($messages)) {
                foreach ($messages as $message) {
                    $message->amount = $this->cost;
                    $message->save(false);
                }
            }
        }
    }

    /**
     * Более быстрое сравнение массивов
     *
     * @param $arrayFrom
     * @param $arrayAgainst
     *
     * @return mixed
     */
    public static function arrayDiffEmulation($arrayFrom, $arrayAgainst) {
        $arrayAgainst = array_flip($arrayAgainst);

        foreach ($arrayFrom as $key => $value) {
            if(isset($arrayAgainst[$value])) {
                unset($arrayFrom[$key]);
            }
        }

        return $arrayFrom;
    }
}
