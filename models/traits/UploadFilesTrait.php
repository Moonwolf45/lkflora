<?php

namespace app\models\traits;

use Yii;

trait UploadFilesTrait {

    public function upload($model_name, $field, $path_to_file = 'undefined', $oldFile = '') {
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

            return true;
        } else {
            return false;
        }
    }

    public function uploadGallery($model_name, $field, $path_to_file = 'undefined') {
        if ($model_name->validate()) {
            foreach ($model_name->$field as $file) {
                $randTempNameFile = time() . '_' . $file->baseName;
                $name_image = $file->baseName . '.' . $file->extension;
                $new_name_image = 'upload/temp_files/' . $randTempNameFile . '.' . $file->extension;
                $path = 'upload/' . $path_to_file . '/' . $name_image;
                shell_exec('convert ' . $new_name_image . ' -auto-orient -quality 90 ' . $path);
                $file->saveAs($path);

                @unlink($new_name_image);
            }
            return true;
        } else {
            return false;
        }
    }
}
