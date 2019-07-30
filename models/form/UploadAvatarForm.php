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

    /**
     * Метод загрузки аватарки
     *
     * @return bool
     */
    public function upload() {
        if ($this->validate()) {
            $name_image = time() . '.' . $this->image->extension;
            $new_name_image = 'upload/temp_files/' . $this->image->baseName . '.' . $this->image->extension;
            $path = 'upload/user/' . $name_image;
            shell_exec('convert ' . $new_name_image . ' -auto-orient -quality 90 ' . $path);
            $this->image->saveAs($path);

            @unlink($new_name_image);
            return $path;
        } else {
            return false;
        }
    }

}
