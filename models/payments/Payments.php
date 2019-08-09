<?php

namespace app\models\payments;

use app\models\db\User;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%payments}}".
 *
 * @property int $id
 * @property int $user_id ID Бренда
 * @property int $service_id ID услуги
 * @property int $type Тип операции
 * @property int $way Способ оплаты
 * @property string $date Дата платежа
 * @property int $invoice_number Номер счета
 * @property string $invoice_date Дата выставления счета
 * @property string $description Описание
 * @property int $status Статус платежа
 *
 * @property User $user
 */
class Payments extends ActiveRecord {

    const TYPE_REFILL = 1;
    const TYPE_WRITEOFF = 0;

    const WAY_CARD = 0;
    const WAY_SCHET = 1;
    const WAY_BALANCE = 2;

    const STATUS_NOTPAID = 0;
    const STATUS_PAID = 1;

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
            [['user_id', 'service_id', 'type', 'way', 'date', 'invoice_date', 'status'], 'required'],
            [['user_id', 'service_id', 'type', 'way', 'invoice_number', 'status'], 'integer'],
            [['date', 'invoice_date'], 'safe'],
            [['description'], 'string'],
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
            'service_id' => 'ID услуги',
            'type' => 'Тип операции',
            'way' => 'Способ оплаты',
            'date' => 'Дата платежа',
            'invoice_number' => 'Номер счета',
            'invoice_date' => 'Дата выставления счета',
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
}
