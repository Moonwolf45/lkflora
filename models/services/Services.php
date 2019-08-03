<?php

namespace app\models\services;

use app\models\addition\Addition;
use app\models\db\User;
use app\models\shops\Shops;
use app\models\tariff\Tariff;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%services}}".
 *
 * @property int $id
 * @property int $user_id ID Бренда
 * @property int $shop_id ID Магазина
 * @property int $tariff_id ID Тарифа
 * @property int $addition_id ID Допа
 * @property string $service_payment_date Дата списания плятежа
 *
 * @property Addition $addition
 * @property Shops $shop
 * @property Tariff $tariff
 * @property User $user
 */
class Services extends ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%services}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['user_id', 'shop_id', 'tariff_id', 'addition_id', 'service_payment_date'], 'required'],
            [['user_id', 'shop_id', 'tariff_id', 'addition_id'], 'integer'],
            [['service_payment_date'], 'date'],
            [['addition_id'], 'exist', 'skipOnError' => true, 'targetClass' => Addition::class, 'targetAttribute' => ['addition_id' => 'id']],
            [['shop_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shops::class, 'targetAttribute' => ['shop_id' => 'id']],
            [['tariff_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tariff::class, 'targetAttribute' => ['tariff_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'ID Бренда',
            'shop_id' => 'ID Магазина',
            'tariff_id' => 'ID Тарифа',
            'addition_id' => 'ID Допа',
            'service_payment_date' => 'Дата списания плятежа',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddition() {
        return $this->hasOne(Addition::class, ['id' => 'addition_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShop() {
        return $this->hasOne(Shops::class, ['id' => 'shop_id']);
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
    public function getUser() {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
