<?php

namespace app\models;

use app\models\db\User;
use yii\base\Model;

class RegistrationForm extends Model
{
    public $email;

    public $name;

    public $pass;

    public function rules()
    {
        return [
            ['email', 'required', 'message' => 'Поле E-mail обязательно к заполнению'],
            ['name', 'required', 'message' => 'Поле имя обязательно к заполнению'],
            ['pass', 'required', 'message' => 'Поле пароль обязательно к заполнению'],

            [['email', 'name', 'pass'], 'required'],
            ['email', 'email'],

        ];
    }

    /**
     * Сохранение
     *
     * @return User|bool
     * @throws \yii\base\Exception
     */
    public function save()
    {
        if ($this->validate()) {
            $user = new User();
            $user->company_name = $this->name;
            $user->email = $this->email;
            $user->setPassword($this->pass);
            $user->generateAuthKey();

            return $user->save() ? $user : false;
        }

        return false;
    }

}