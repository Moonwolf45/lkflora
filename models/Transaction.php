<?php

namespace app\models;

use app\models\db\User;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%transaction}}".
 *
 * @property int $user_id ID Бренда
 * @property string $transaction_id ID Транзации
 * @property string $payment_id ID Зачисления
 * @property int $status Статус
 *
 * @property User $user
 */
class Transaction extends ActiveRecord {

    const STATUS_OK = 1;
    const STATUS_REPEAT = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%transaction}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['user_id', 'transaction_id', 'status'], 'required'],
            [['user_id', 'payment_id', 'status'], 'integer'],
            [['transaction_id'], 'string', 'max' => 255],
            [['user_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'user_id' => 'ID Бренда',
            'transaction_id' => 'ID Транзации',
            'payment_id' => 'ID Зачисления',
            'status' => 'Статус',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
