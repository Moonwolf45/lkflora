<?php

namespace app\models;

use app\models\db\User;
use Yii;
use yii\base\Model;

/**
 * Форма авторизации
 * @package app\models
 */
class AuthForm extends Model {

    public $email;
    public $password;
    public $rememberMe = false;

    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['email', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Валидация пароля
     *
     * @param string $attribute the attribute currently being validated
     * @param array  $params    the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'E-mail\пароль введены не верно.');
            }
        }
    }

    /**
     * Авторизация
     *
     * @return bool whether the user is logged in successfully
     */
    public function login() {
        if ($this->validate()) {
            $key = $this->getUser();
            $key->generateAuthKey();
            $key->save();

            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    /**
     * Поиск пользователя по email
     *
     * @return User|null
     */
    protected function getUser() {
        if ($this->_user === null) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
}
