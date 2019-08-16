<?php

namespace app\models\payments;


use yii\base\Model;

class NewPaid extends Model {

    public $amount;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['amount'], 'required'],
            [['amount'], 'number']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'amount' => 'Пополнить баланс',
        ];
    }

}
