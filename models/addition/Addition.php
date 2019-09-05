<?php

namespace app\models\addition;

use app\models\MessageToPaid;
use app\models\service\Service;
use app\models\shops\Shops;
use app\models\tariff\Tariff;
use app\models\TariffAddition;
use app\models\TariffAdditionQuantity;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%addition}}".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 * @property string $name Название
 * @property string $cost Стоимость
 * @property string $about Описание
 * @property int $type Тип
 *
 * @property TariffAdditionQuantity[] $tariffAdditionsQty
 * @property TariffAddition[] $tariffAdditions
 * @property Tariff[] $tariffs
 */
class Addition extends ActiveRecord {

    const TYPE_NOT_REPEAT = 0;
    const TYPE_REPEAT = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%addition}}';
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
    public function rules() {
        return [
            [['name', 'cost', 'type'], 'required'],
            [['type'], 'integer'],
            [['cost'], 'number'],
            [['name', 'about'], 'string', 'max' => 255],
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
            'type' => 'Тип',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getTariffs() {
        return $this->hasMany(Tariff::class, ['id' => 'tariff_id'])->viaTable('{{%tariff_addition}}', ['addition_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getTariffsQuantity() {
        return $this->hasMany(Tariff::class, ['id' => 'tariff_id'])->viaTable('{{%tariff_addition_quantity}}', ['addition_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getShops() {
        return $this->hasMany(Shops::class, ['id' => 'shop_id'])->viaTable('{{%shops_addition}}', ['addition_id' => 'id']);
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
            $services = Service::find()->where(['type_service' => Service::TYPE_ADDITION])->andWhere(['OR',
                'type_serviceId' => $this->id, 'old_service_id' => $this->id])->all();
            if (!empty($services)) {
                foreach ($services as $service) {
                    if ($service->type_serviceId == $this->id) {
                        if ($service->writeoff_amount != 0) {
                            $service->writeoff_amount = $this->cost;
                        }
                        $service->old_writeoff_amount = $this->cost;
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
                    if ($message->amount != 0) {
                        $message->amount = $this->cost;
                        $message->save(false);
                    }
                }
            }
        }
    }
}
