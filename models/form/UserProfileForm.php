<?php

namespace app\models\form;

use app\models\db\User;
use Yii;
use yii\base\Model;
use yii\db\Exception;

/**
 * Форма сохранения данных на странице user/settings
 * @package app\models\form
 */
class UserProfileForm extends Model {

    public $email;
    public $company_name;
    public $phone;
    public $current_pass;
    public $new_pass;
    public $repeat_new_pass;
    public $image;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['email', 'company_name', 'current_pass', 'new_pass', 'repeat_new_pass', 'phone'], 'string'],
            [['image'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 4]
        ];
    }

    /**
     * Сохранение модели
     *
     * @return bool
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function save() {
        if (!$this->validate()) {
            return false;
        }

        $userId = Yii::$app->user->id;
        $userProfile = User::findOne(['id' => $userId]);

        if (!$userProfile) {
            throw new Exception('Пользователь не авторизован');
        }

        $userProfile->email = htmlspecialchars(strip_tags($this->email));
        $userProfile->phone = $this->phone;
        $userProfile->company_name = htmlspecialchars(strip_tags($this->company_name));

        if ($this->current_pass != '' && $this->new_pass != '' && $this->repeat_new_pass != '') {
            if ($this->new_pass == $this->repeat_new_pass) {
                $currentPass = $this->current_pass;
                $user_id = Yii::$app->user->id;
                $user = User::findOne(['id' => $user_id]);

                if ($user && $user->validatePassword($currentPass)) {
                    $userProfile->password_hash = Yii::$app->security->generatePasswordHash($this->new_pass);
                } else {
                    $error = 'Введенный старый пароль не верен';
                }
            } else {
                $error = 'Новые введенные пароли не совпадают друг с другом';
            }
        }

        if (!$error) {
            return $userProfile->save();
        }

        return true;
    }

    /**
     *  Метод заполнения модели данными
     */
    public function loadData() {
        $userId = Yii::$app->user->id;
        $userProfile = User::findOne(['id' => $userId]);

        if ($userProfile) {
            $this->email = $userProfile->email;
            $this->phone = $userProfile->phone;
            $this->company_name = $userProfile->company_name;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'image' => false,
        ];
    }
}
