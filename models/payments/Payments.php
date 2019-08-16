<?php

namespace app\models\payments;

use app\models\addition\Addition;
use app\models\db\User;
use app\models\shops\Shops;
use app\models\tariff\Tariff;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%payments}}".
 *
 * @property int $id
 * @property int $user_id ID Бренда
 * @property int $shop_id ID Магазина
 * @property int $service_id ID услуги
 * @property int $type Тип операции
 * @property int $way Способ оплаты
 * @property string $date Дата платежа
 * @property int $invoice_number Номер счета
 * @property string $invoice_date Дата выставления счета
 * @property float|int amount Сумма
 * @property string $description Описание
 * @property int $status Статус платежа
 *
 * @property User $user
 * @property Shops $shop
 * @property Tariff $tariff
 * @property Addition[] $addition
 */
class Payments extends ActiveRecord {

    const TYPE_REFILL = 1;
    const TYPE_WRITEOFF = 0;

    const WAY_CARD = 0;
    const WAY_SCHET = 1;
    const WAY_BALANCE = 2;

    const STATUS_CANCEL = 0;
    const STATUS_PAID = 1;
    const STATUS_EXPOSED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%payments}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['user_id', 'shop_id', 'service_id', 'type', 'way', 'date', 'invoice_date', 'status'], 'required'],
            [['user_id', 'shop_id', 'service_id', 'type', 'way', 'invoice_number', 'status'], 'integer'],
            ['amount', 'number'],
            [['date', 'invoice_date'], 'safe'],
            [['description'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'shop_id' => 'Магазин',
            'service_id' => 'ID услуги',
            'type' => 'Тип операции',
            'way' => 'Способ оплаты',
            'date' => 'Дата платежа',
            'invoice_number' => 'Номер счета',
            'invoice_date' => 'Дата выставления счета',
            'amount' => 'Сумма',
            'description' => 'Описание',
            'status' => 'Статус платежа',
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
    public function getShop() {
        return $this->hasOne(Shops::class, ['id' => 'shop_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTariff() {
        return $this->hasOne(Tariff::class, ['id' => 'service_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddition() {
        return $this->hasOne(Addition::class, ['id' => 'service_id']);
    }

    /**
     * @return array|ActiveRecord|null
     */
    public static function getMaxId() {
        return static::find()->select(['id'])->orderBy(['id' => SORT_DESC])->asArray()->limit(1)->one();
    }

    /**
     * @return array|ActiveRecord|null
     */
    public static function getMaxNumberSchet() {
        return static::find()->select(['invoice_number'])->andWhere(['!=', 'invoice_number', ''])
            ->orderBy(['invoice_number' => SORT_DESC])->asArray()->limit(1)->one();
    }
}
