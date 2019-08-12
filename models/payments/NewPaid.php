<?php

namespace app\models\payments;


use yii\base\Model;

class NewPaid extends Model {

    public $newPaid;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['newPaid'], 'required'],
            [['newPaid'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'newPaid' => 'Пополнить баланс',
        ];
    }

}
