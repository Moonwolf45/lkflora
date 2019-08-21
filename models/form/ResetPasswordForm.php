<?php

namespace app\models\form;

use app\models\db\User;
use app\models\traits\MailToUserTrait;
use yii\base\Model;
use yii\helpers\Url;

/**
 * Форма восстановления пароля
 * @package app\models\form
 */
class ResetPasswordForm extends Model {
    use MailToUserTrait;

    public $email;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
        ];
    }

    /**
     * Генерация токена, внесение токена в бд и отправка мыла пользователю
     *
     * @return bool
     * @throws \yii\base\Exception
     */
    public function update() {
        if (!$this->validate()) {
            return false;
        }

        if (!$user = User::findByEmail($this->email)) {
            return false;
        }

        $user->generatePasswordResetToken();

        if ($user->save()) {
            $link = Url::to(['/site/reset-password-confirm', 'token' => $user->password_reset_token], true);
            $this->sendMailToUser($user->email, 'request', 'Восстановление пароля на сайте Florapoint',
                ['link' => $link]);

            return true;
        }

        return false;
    }

}
