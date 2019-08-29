<?php

namespace app\models;

use app\models\db\User;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%message_to_paid}}".
 *
 * @property int $user_id ID Бренда
 * @property string $service_type Услуга
 * @property string $service_id ID Услуги
 * @property string $date_to_paid Дата оплаты
 * @property string $amount Сумма
 * @property int $debtor Должник
 *
 * @property User $user
 */
class MessageToPaid extends ActiveRecord {

    const DEBTOR_YES = 1;
    const DEBTOR_NO = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%message_to_paid}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['user_id', 'service_type', 'date_to_paid', 'service_id', 'amount'], 'required'],
            [['user_id', 'service_type', 'service_id'], 'integer'],
            [['debtor'], 'boolean', 'trueValue' => true, 'falseValue' => false],
            [['date_to_paid'], 'date'],
            [['amount'], 'number'],
            [['user_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'user_id' => 'ID Бренда',
            'service_type' => 'Услуга',
            'service_id' => 'ID Услуги',
            'date_to_paid' => 'Дата оплаты',
            'amount' => 'Сумма',
            'debtor' => 'Должник',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
