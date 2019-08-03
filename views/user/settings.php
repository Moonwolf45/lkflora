<?php
/** @var $userSettingsData \app\models\db\UserSettings */

/** @var $profileSettings \app\models\db\User */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Настройки'; ?>

<div class="content">
    <h2 class="content__title">Настройки</h2>
    <div class="content__row">
        <div class="content__col-12">
            <div class="content__box">
                <div class="settings">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="settings__row">
                        <div class="settings__col settings__col_v-middle">
                            <div class="settings__box">
                                <style>
                                    .unloadAvaInp .form-group {
                                        padding: 0;
                                        margin: 0;
                                    }

                                    .unloadAvaInp input[type=file] {
                                        outline: 0;
                                        opacity: 0;
                                        pointer-events: none;
                                        user-select: none
                                    }

                                    .unloadAvaInp .label {
                                        display: block;
                                        padding: 0;
                                        cursor: pointer;
                                    }

                                    .unloadAvaInp .label i {
                                        display: block;
                                        font-size: 42px;
                                        padding-bottom: 16px
                                    }

                                    .unloadAvaInp .label i, .unloadAvaInp .label .title {
                                        color: grey;
                                        transition: 200ms color
                                    }

                                    .unloadAvaInp .label:hover i, .unloadAvaInp .label:hover .title {
                                        color: #000
                                    }
                                </style>
                                <div class="unloadAvaInp">
                                    <div class="form-group">
                                        <label class="label">
                                            <?php $avatar = '';
                                            if (Yii::$app->user->identity->avatar) {
                                                $avatar = Yii::$app->user->identity->avatar;
                                            } else {
                                                $avatar = 'images/group.svg';
                                            } ?>
                                            <?=Html::img('@web/' . $avatar, ['alt' => 'Ваше фото', 'id' => 'for-preview',
                                                'style' => 'width:150px;height:150px;border-radius:50%;']); ?>
                                            <?= $form->field($model, 'image', ['options' => ['id' => 'imgInp',
                                                'onchange' => 'previewFile()']])->fileInput(); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="settings__col settings__col_v-middle settings__col_offset">
                            <div class="settings__box">
                                <div class="field">
                                    <p class="setting__contract">
                                        Номер договора:
                                        <span><?= $profileSettings->doc_num ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="settings__col">
                            <div class="settings__box">
                                <div class="field settings__field settings__field-mt50">
                                    <p class="field__text">Ваш email</p>
                                    <div class="settings__mail-block">
                                        <div class="settings__mail">
                                            <span class="settings__mail-box">
                                                <span class="settings__mail-text"><?= $profileSettings->email ?></span>
                                                <span class="settings__mail-edit js_mail-edit">
                                                    <?=Html::img('@web/images/icon/edit.svg'); ?>
                                                </span>
                                            </span>
                                        </div>

                                        <?= Html::activeInput('text', $model, 'email', ['class' => 'input settings__input js_email-input-edit']) ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="settings__col">
                            <div class="settings__box">
                                <div class="field settings__field settings__field-mt50">
                                    <p class="field__text">Название компании</p>
                                    <?= $form->field($model, 'company_name', [
                                        'inputOptions' => ['class' => 'input'],
                                    ])->label('')->textInput(['placeholder' => ""]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="s-line"></div>
                    <div class="little-title settings__little-title">Изменить пароль</div>
                    <div class="settings__row">
                        <div class="settings__col">
                            <div class="settings__box">
                                <div class="field settings__field settings__field-mt50">
                                    <p class="field__text">Текущий пароль</p>
                                    <?= Html::activeInput('password', $model, 'current_pass', [
                                        'class'       => 'input',
                                        'placeholder' => '',
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                        <div class="settings__col">
                            <div class="settings__box">
                                <div class="field settings__field settings__field-mt50">
                                    <p class="field__text">Новый пароль</p>
                                    <?= Html::activeInput('password', $model, 'new_pass', [
                                        'class'       => 'input',
                                        'placeholder' => '',
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                        <div class="settings__col">
                            <div class="settings__box">
                                <div class="field settings__field settings__field-mt50">
                                    <p class="field__text">Повторите новый пароль</p>
                                    <?= Html::activeInput('password', $model, 'repeat_new_pass', [
                                        'class'       => 'input',
                                        'placeholder' => '',
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="saveBtn"></div>
                    <button class="button button_width-200px settings__button">Сохранить</button>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // костыли костыльные для фоновой загрузка авы для предпросмотра
    function previewFile() {
        var preview = document.getElementById('for-preview');
        var file = document.querySelector('input[type=file]').files[0];
        var reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
        }
    }
</script>

<?php $script = <<< JS
    function checkPass() {
        var currentPass = $('#userprofileform-current_pass').val();
        var newPass = $('#userprofileform-new_pass').val();
        var repeatNewPass = $('#userprofileform-repeat_new_pass').val();
        
        if (currentPass == '') { 
            return false; 
        }
        if (newPass == '') { 
            return false; 
        }
        if (repeatNewPass == '') { 
            return false; 
        }
        
        if (newPass == repeatNewPass) {
            return true;
        }
    }
    
    function disableSaveButton() {
        $('#saveBtn').html('<br>Данные сохранятся без изменения пароля т.к. в изменении пароля не заполнены все поля<br>');
    }
    
    function enableSaveButton() {
        $('#saveBtn').html('');
    }
    
    $('#userprofileform-current_pass').on('input keyup', function(e) {
        var currentPass = $('#userprofileform-current_pass').val();
        if (checkPass()) { 
            enableSaveButton(); 
        } else { 
            disableSaveButton(); 
        }
    });

    $('#userprofileform-new_pass').on('input keyup', function(e) {
        if (checkPass()) { 
            enableSaveButton(); 
        } else { 
            disableSaveButton(); 
        }
    });
    
    $('#userprofileform-repeat_new_pass').on('input keyup', function(e) {
        if (checkPass()) { 
            enableSaveButton(); 
        } else { 
            disableSaveButton(); 
        }
    });
JS;

$this->registerJs($script, yii\web\View::POS_READY); ?>
