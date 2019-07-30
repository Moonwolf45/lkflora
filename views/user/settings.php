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
                                        padding: 0em;
                                        margin: 0em
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
                                            <img src="/<?php
                                            if (Yii::$app->user->identity->avatar) {
                                                echo Yii::$app->user->identity->avatar;
                                            } else {
                                                echo 'images/Group.svg';
                                            } ?>" alt="Ваше фото" id="for-preview" style="width:150px;height:150px;border-radius:50%;">
                                            <?= $form->field($model, 'image', ['options' => ['id' => 'imgInp', 'onchange' => 'previewFile()']])->fileInput() ?>
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
                                                    <svg width="14" height="17" viewBox="0 0 14 17"
                                                         fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M13.6455 3.6263L11.5461 1.35009C11.2599 1.0398 10.9464 0.891907 10.5783 0.891907C10.2647 0.891907 9.98062 1.02715 9.73312 1.29112L8.05657 3.07957L1.36307 10.3365C1.24036 10.4992 1.17755 10.6336 1.15864 10.7061L0.0407087 15.007C0.0174702 15.0968 0 15.1842 0 15.2731C0 15.6427 0.327149 16.027 0.749737 16.027C0.845133 16.027 0.913754 16.0144 0.967851 15.9974L4.90758 14.7557C5.04108 14.7138 5.15273 14.6668 5.23473 14.5785L11.9689 7.2919L13.6182 5.5034C13.8363 5.26692 13.959 4.97118 14 4.61675C14 4.21754 13.8773 3.87763 13.6455 3.6263ZM4.32127 13.2778L1.84031 14.0317L2.56268 11.3417L8.58816 4.80873L10.3738 6.71541L4.32127 13.2778ZM11.437 5.56233L9.6514 3.65565L10.6056 2.62111L12.3641 4.52779L11.437 5.56233Z"
                                                              fill="#E4E8F0"/>
                                                    </svg>
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
