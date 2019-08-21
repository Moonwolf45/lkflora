<?php

namespace app\models\form;

use yii\base\Model;

/**
 * Модель загрузки аватарки профиля
 * @package app\models
 */
class UploadAvatarForm extends Model {

    public $image;

    /**
     * Правила загрузки аватарки
     *
     * @return array
     */
    public function rules() {
        return [
            [['image'], 'file', 'extensions' => 'png, jpg', 'maxSize' => 1024 * 1024 * 5]
        ];
    }
}
