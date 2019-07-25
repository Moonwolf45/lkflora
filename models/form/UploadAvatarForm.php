<?php

namespace app\models\form;

use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Модель загрузки аватарки профиля
 * @package app\models
 */
class UploadAvatarForm extends Model
{

    public $image;

    /**
     * Правила загрузки аватарки
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['image'], 'file', 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * Метод загрузки аватарки
     *
     * @return bool
     */
    public function upload()
    {
        if ($this->validate()) {
            $this->image->saveAs("upload/{$this->image->baseName}.{$this->image->extension}");
        } else {
            return false;
        }
    }

}