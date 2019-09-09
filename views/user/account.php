<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/** @var $profileSettings app\models\db\UserSettings */

$this->title = 'Анкета'; ?>

<div class="content">
    <h2 class="content__title">Анкета</h2>
    <div class="content__row">
        <div class="content__col-12">
            <div class="content__box">
                <div class="profile">
                    <div class="little-title">Реквизиты</div>
                    <?php $form = ActiveForm::begin(); ?>
                        <div class="profile__row">
                            <div class="profile__col">
                                <div class="profile__box">
                                    <div class="profile__field">
                                        <p class="field__text point-event-opacity"></p>
                                        <div class="profile__block-radio">
                                            <?php if ($profileSettings['type_org'] == '') {
                                                $profileSettings['type_org'] == 'ooo';
                                            } ?>
                                            <label class="radio radio_margin-r-35px">
                                                <input class="radio__radio" <?php if ($profileSettings['type_org'] == 'ip') {
                                                    echo 'checked';
                                                } ?> type="radio" name="UserSettingsForm[type_org]" value="ip" id="type-ip">
                                                <div class="radio__nesting">
                                                    <span class="radio__circle"></span>
                                                    <span class="radio__text">ИП</span>
                                                </div>
                                            </label>
                                            <label class="radio">
                                                <input class="radio__radio" <?php if ($profileSettings['type_org'] == 'ooo') {
                                                    echo 'checked';
                                                } ?> type="radio" name="UserSettingsForm[type_org]" value="ooo" id="type-ooo">
                                                <div class="radio__nesting">
                                                    <span class="radio__circle"></span>
                                                    <span class="radio__text">ООО / АО / ЗАО </span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="profile__col">
                                <div class="profile__box">
                                    <div class="field profile__field">
                                        <p class="field__text">Наименование</p>
                                        <?= $form->field($model, 'name_org', [
                                            'inputOptions' => ['class' => 'input', 'id' => 'party'],
                                        ])->label(false); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="profile__col">
                                <div class="profile__box">
                                    <div class="field profile__field ">
                                        <p class="field__text">Юридический адрес</p>
                                        <?= $form->field($model, 'ur_addr_org', [
                                            'inputOptions' => ['class' => 'input', 'id' => 'address'],
                                        ])->label(false); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="profile__col">
                                <div class="profile__box">
                                    <div class="field profile__field">
                                        <p class="field__text">ОГРН / ОГРНИП</p>
                                        <?= $form->field($model, 'ogrn', [
                                            'inputOptions' => ['class' => 'input', 'id' => 'ogrn'],
                                        ])->label(false); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="profile__col">
                                <div class="profile__box">
                                    <div class="field profile__field">
                                        <p class="field__text">ИНН</p>
                                        <?= $form->field($model, 'inn', [
                                            'inputOptions' => ['class' => 'input', 'id' => 'inn'],
                                        ])->label(false); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="s-line s-line_row"></div>
                            <div class="profile__col">
                                <div class="profile__box">
                                    <div class="field profile__field <?php if ($profileSettings['type_org'] == 'ip') {
                                        echo 'field_disable';
                                    } ?>" id='kpp-div'>
                                        <p class="field__text">КПП</p>
                                        <?= $form->field($model, 'kpp', ['inputOptions' => ['class' => 'input',
                                            'id' => 'kpp']])->label(false); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="profile__col">
                                <div class="profile__box">
                                    <div class="field profile__field">
                                        <p class="field__text">Бик банка</p>
                                        <?= $form->field($model, 'bank_bic', ['inputOptions' => [
                                            'class' => 'input']])->label(false); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="profile__col">
                                <div class="profile__box">
                                    <div class="field profile__field">
                                        <p class="field__text">название банка</p>
                                        <?= $form->field($model, 'bank_name', ['inputOptions' => [
                                            'class' => 'input', 'id' => 'bank']])->label(false); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="profile__col">
                                <div class="profile__box">
                                    <div class="field profile__field">
                                        <p class="field__text">кор счет</p>
                                        <?= $form->field($model, 'kor_schet', ['inputOptions' => [
                                            'class' => 'input']])->label(false); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="profile__col">
                                <div class="profile__box">
                                    <div class="field profile__field">
                                        <p class="field__text">рассчетный счет</p>
                                        <?= $form->field($model, 'rass_schet', ['inputOptions' => [
                                            'class' => 'input']])->label(false); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?= Html::submitButton('Сохранить', ['class' => 'button button_width-200px profile__button']) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$script = <<< JS
    $('input[name="UserSettingsForm[type_org]"]').on('change', function(e) {
         if ($('#type-ip').prop("checked")) {
             $('#kpp-div').addClass('field_disable');
         } else {
             $('#kpp-div').removeClass('field_disable');
         }
    });
JS;

$this->registerJs($script, View::POS_READY); ?>
