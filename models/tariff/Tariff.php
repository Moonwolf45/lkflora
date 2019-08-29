<?php

namespace app\models\tariff;

use app\models\addition\Addition;
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
 * @property int $resolutionServiceQuantity Количество
 */
class Tariff extends ActiveRecord {

    public $resolutionService = [];
    public $resolutionServiceQuantity = [];
    public $connectedServices;

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
            [['resolutionService', 'connectedServices'], 'each', 'rule' => ['integer']],
            [['resolutionServiceQuantity'], 'each', 'rule' => ['number', 'min' => 0, 'max' => 999]],
            ['resolutionServiceQuantity', 'default', 'value' => 1],
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
            'connectedServices' => 'Доп. улсуги которые подключены по умолчанию в тарифе',
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
        return $this->hasMany(TariffAddition::class, ['tariff_id' => 'id']);
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
        return $this->hasMany(TariffAdditionQuantity::class, ['tariff_id' => 'id']);
    }

    /**
     * Получаем значение дропа
     *
     * @param string $i
     *
     * @param bool $data
     *
     * @return array|mixed
     */
    public static function getDrop($data = false, $i = '') {
        $dropArray = [
            self::DROP_TRUE => 'Да',
            self::DROP_FALSE => 'Нет',
        ];

        if ($data) {
            return $dropArray;
        } else {
            return $dropArray[$i];
        }
    }

    /**
     * Получаем значение статуса
     *
     * @param string $i
     *
     * @param bool $data
     *
     * @return array|mixed
     */
    public static function getStatus($data = false, $i = '') {
        $statusArray = [
            self::STATUS_ON => 'Включен',
            self::STATUS_OFF => 'Выключен',
        ];

        if ($data) {
            return $statusArray;
        } else {
            return $statusArray[$i];
        }
    }
}
