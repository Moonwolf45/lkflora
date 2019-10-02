<?php

namespace app\models\traits;

use app\models\tickets\TicketsFiles;
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
            if (!file_exists('upload/' . $path_to_file)) {
                mkdir('upload/' . $path_to_file, 0755);
            }
        } else {
            mkdir('upload', 0755);
            mkdir('upload/' . $path_to_file, 0755);
        }

        if ($model_name->validate()) {
            $name_image = time() . '.' . $model_name->$field->extension;
            $new_name_image = 'upload/temp_files/' . $model_name->$field->baseName . '.' . $model_name->$field->extension;
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
            if (!file_exists('upload/' . $path_to_file)) {
                mkdir('upload/' . $path_to_file, 0755);
            }
        } else {
            mkdir('upload', 0755);
            mkdir('upload/' . $path_to_file, 0755);
        }

        $arrFile = [];
        if ($model_name->validate()) {
            foreach ($model_name->$field as $key => $file) {
                $randTempNameFile = time() . '_' . preg_replace("/[^ \w]+/", "_", $file->baseName) . '.' . $file->extension;
                $name_image = time() . '.' . $file->extension;
                if ($file->type == 'image/jpeg' || $file->type == 'image/pjpeg' || $file->type == 'image/png') {
                        $new_name_image = 'upload/temp_files/' . $randTempNameFile;
                        $path = 'upload/' . $path_to_file . '/' . $name_image;
                        shell_exec('convert ' . $new_name_image . ' -auto-orient -quality 90 ' . $path);
                        $file->saveAs($path);
                        $model_name->$field[$key]->saveAs($path);

                        $arrFile[$key]['type'] = TicketsFiles::TYPE_IMAGE;
                        $arrFile[$key]['path'] = $path;
                        $arrFile[$key]['name'] = $name_image;

                    @unlink($new_name_image);
                } else {
                    $path = 'upload/' . $path_to_file . '/' . preg_replace("/[^ \w]+/", "_", $file->baseName) . '.' . $file->extension;
                    $file->saveAs($path);
                    $model_name->$field[$key]->saveAs($path);

                    $arrFile[$key]['type'] = TicketsFiles::TYPE_FILE;
                    $arrFile[$key]['path'] = $path;
                    $arrFile[$key]['name'] = preg_replace("/[^ \w]+/", "_", $file->baseName);
                }
            }

            return $arrFile;
        } else {
            return false;
        }
    }

    /**
     * Функция удаления файлов
     *
     * @param $model_name
     * @param $field
     *
     * @param string $type_model
     *
     * @return bool|string
     */
    public function deleteImages($model_name, $field, $type_model = 'array') {
        if ($type_model == 'array') {
            foreach ($model_name->$field as $file) {
                if (file_exists($file->file)) {
                    @unlink($file->file);
                }
            }
        } else {
            if (file_exists($model_name->$field->file)) {
                @unlink($model_name->$field->file);
            }
        }

        return true;
    }
}
