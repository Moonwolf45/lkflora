<?php

namespace app\models\form;

use app\models\db\User;
use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\helpers\Url;
use app\components\Notifications;

/**
 * Форма восстановления пароля
 * @package app\models\form
 */
class ResetPasswordForm extends Model
{
    public $email;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
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
    public function update()
    {
        if (!$this->validate()) {
            return false;
        }

        if (!$user = User::findByEmail($this->email)) {
            return false;
        }

        $user->generatePasswordResetToken();

        if ($user->save()) {
            $link = Url::to(['/site/reset-password-confirm', 'token' => $user->password_reset_token], true);
            mail($this->email, 'Восстановление пароля на сайте Florapoint', $link);

            return true;
        }

        return false;
    }

}