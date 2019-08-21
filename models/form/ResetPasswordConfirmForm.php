<?php

namespace app\models\form;

use app\models\db\User;
use yii\base\Model;

/**
 * Создание нового пароля после формы запроса восстановления пароля
 *
 * @package app\models\forms\reset
 */
class ResetPasswordConfirmForm extends Model {
    /** @var string Пароль */
    public $password;

    /** @var string Повтороение пароля */
    public $password_repeat;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'password_repeat'], 'trim'],
            [['password', 'password_repeat'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'password_repeat' => 'Повторите пароль',
        ];
    }

    /**
     * Сохранение нового пароля
     *
     * @param User $user
     *
     * @return bool
     * @throws \yii\base\Exception
     */
    public function update(User $user) {
        if (!$this->validate()) {
            return false;
        }

        $user->setPassword($this->password);

        return $user->save();
    }

}
