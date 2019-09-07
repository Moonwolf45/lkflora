<?php

namespace app\models\shops;

use app\models\addition\Addition;
use app\models\db\User;
use app\models\service\Service;
use app\models\ShopsAddition;
use app\models\tariff\Tariff;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%shops}}".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 * @property string $address Адрес магазина
 * @property int $tariff_id Привязка к тарифу
 * @property int $user_id Привязка к пользователю
 * @property int $on_check На проверке
 * @property int $deleted Удалён
 *
 * @property User $user
 * @property Tariff $tariff
 * @property Addition[] $additions
 * @property ShopsAddition[] $shopsAdditions
 */
class Shops extends ActiveRecord {

    public $addition = [];
    public $quantityArr = [];
    public $quantity = 1;

    const DELETED_TRUE = 1;
    const DELETED_FALSE = 0;

    const ON_CHECK_TRUE = 1;
    const ON_CHECK_FALSE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%shops}}';
    }

    /**
     * @return array
     */
    public function behaviors() {
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
            [['address', 'tariff_id', 'user_id'], 'required'],
            [['tariff_id', 'user_id', 'deleted'], 'integer'],
            [['on_check'], 'boolean', 'trueValue' => true, 'falseValue' => false],
            [['addition', 'quantityArr'], 'each', 'rule' => ['integer', 'max' => 100]],
            ['quantity', 'number', 'min' => 0, 'max' => 9999],
            ['quantity', 'default', 'value' => 0],
            [['address'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']],
            [['tariff_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tariff::class,
                'targetAttribute' => ['tariff_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(){
        return [
            'id' => 'ID',
            'address' => 'Адрес магазина',
            'tariff_id' => 'Привязка к тарифу',
            'user_id' => 'Привязка к бренду',
            'addition' => 'Доп. услуги',
            'quantity' => 'Количество',
            'quantityArr' => 'Количество',
            'on_check' => 'На проверке',
            'deleted' => 'Удалён',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTariff() {
        return $this->hasOne(Tariff::class, ['id' => 'tariff_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditions() {
        return $this->hasMany(Addition::class, ['id' => 'addition_id'])->via('shopsAdditions');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopsAdditions() {
        return $this->hasMany(ShopsAddition::class, ['shop_id' => 'id'])->indexBy(function($row) {
            return $row['shop_id'] . '_' . $row['addition_id'];
        });
    }

    public function getServices() {
        return $this->hasMany(Service::class, ['shop_id' => 'id']);
    }
}
