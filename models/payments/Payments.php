<?php

namespace app\models\payments;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%payments}}".
 *
 * @property int $id
 * @property int $type Тип операции
 * @property int $user_id ID Бренда
 * @property int $way Способ оплаты
 * @property string $date Дата платежа
 * @property int $invoice_number Номер счета
 * @property string $invoice_date Дата выставления счета
 * @property int $status Статус платежа
 * @property int $service_id ID услуги
 */
class Payments extends ActiveRecord {
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
            [['type', 'user_id', 'way', 'date', 'invoice_number', 'invoice_date', 'status', 'service_id'], 'required'],
            [['type', 'user_id', 'way', 'invoice_number', 'status', 'service_id'], 'integer'],
            [['date', 'invoice_date'], 'date'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'type' => 'Тип операции',
            'user_id' => 'ID Бренда',
            'way' => 'Способ оплаты',
            'date' => 'Дата платежа',
            'invoice_number' => 'Номер счета',
            'invoice_date' => 'Дата выставления счета',
            'status' => 'Статус платежа',
            'service_id' => 'ID услуги',
        ];
    }
}
