<?php

namespace app\models\traits;

use Yii;

trait UploadFilesTrait {

    /**
     * Функция загрузки изображения
     *
     * @param $model_name
     * @param $field
     * @param string $path_to_file
     * @param string $oldFile
     *
     * @return bool|string
     */
    public function uploadImage($model_name, $field, $path_to_file = 'undefined', $oldFile = '') {
        if (file_exists('upload')) {
            if (!file_exists($path_to_file)) {
                mkdir($path_to_file, 0755);
            }
        } else {
            mkdir('upload', 0755);
            mkdir($path_to_file, 0755);
        }

        if ($model_name->validate()) {
            $name_image = $model_name->$field->baseName . '.' . $model_name->$field->extension;
            $new_name_image = 'upload/temp_files/' . time() . '.' . $model_name->$field->extension;
            $path = 'upload/' . $path_to_file . '/' . $name_image;
            shell_exec('convert ' . $new_name_image . ' -auto-orient -quality 90 ' . $path);
            $model_name->$field->saveAs($path);

            @unlink($new_name_image);
            if ($oldFile != '') {
                @unlink($oldFile);
            }

            return $path;
        } else {
            return false;
        }
    }

    /**
     * Функция загрузки нескольких изображений
     *
     * @param $model_name
     * @param $field
     * @param string $path_to_file
     *
     * @return array|bool
     */
    public function uploadGallery($model_name, $field, $path_to_file = 'undefined') {
        if (file_exists('upload')) {
            if (!file_exists($path_to_file)) {
                mkdir($path_to_file, 0755);
            }
        } else {
            mkdir('upload', 0755);
            mkdir($path_to_file, 0755);
        }

        $arrFile = [];
        if ($model_name->validate()) {
            foreach ($model_name->$field as $key => $file) {
                $randTempNameFile = time() . '_' . $file->baseName . '.' . $file->extension;
                $name_image = $file->baseName . '.' . $file->extension;
                $new_name_image = 'upload/temp_files/' . $randTempNameFile;
                $path = 'upload/' . $path_to_file . '/' . $name_image;
                shell_exec('convert ' . $new_name_image . ' -auto-orient -quality 90 ' . $path);
                $file->saveAs($path);
                $model_name->$field[$key]->saveAs($path);
                $arrFile[$key] = $path;

                @unlink($new_name_image);
            }

            return $arrFile;
        } else {
            return false;
        }
    }
}
