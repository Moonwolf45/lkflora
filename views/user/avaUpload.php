<?php
/** @var $userSettingsData \app\models\db\UserSettings */

/** @var $profileSettings \app\models\db\User */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Ваше фото';
?>
<div class="content">
    <h2 class="content__title">Ваше фото</h2>
    <div class="content__row">
        <div class="content__col-12">
            <div class="content__box">
                <div class="settings">
                    <?php $form = ActiveForm::begin(); ?>
                    <div style="text-align:center;">
                        <b>Ваше текущее фото:</b><br><br>
                        <img src="/<?php
                        if (Yii::$app->user->identity->avatar) {
                            echo Yii::$app->user->identity->avatar;
                        } else {
                            echo 'images/user-photo.png'; } ?>" alt="Ваше фото"
                             style="width:150px;height:150px;border-radius:50%;">
                        <br><br>
                        <?= $form->field($model, 'image')->fileInput() ?>


                        <style>
                            .unloadAvaInp .form-group{padding:1em;margin:1em}
                            .unloadAvaInp input[type=file]{outline:0;opacity:0;pointer-events:none;user-select:none}
                            .unloadAvaInp .label{width:150px;display:block;padding:1.2em;transition:border 300ms ease;cursor:pointer;text-align:center}
                            .unloadAvaInp .label i{display:block;font-size:42px;padding-bottom:16px}
                            .unloadAvaInp .label i,.unloadAvaInp .label .title{color:grey;transition:200ms color}
                            .unloadAvaInp .label:hover i,.unloadAvaInp .label:hover .title{color:#000}
                        </style>
                        <div class="unloadAvaInp">
                            <div class="form-group">
                                <label class="label">
                                    <img src="/<?php
                                    if (Yii::$app->user->identity->avatar) {
                                        echo Yii::$app->user->identity->avatar;
                                    } else {
                                        echo 'images/user-photo.png'; } ?>" alt="Ваше фото"
                                         style="width:150px;height:150px;border-radius:50%;">
                                    <input type="file">
                                </label>
                            </div>
                        </div>

                        <button class="button button_width-200px settings__button">Сохранить</button>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
