<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var TYPE_NAME $newTicket */

?>

<div class="jsx-modal" data-jsx-modal-id="appeal">
    <div class="jsx-modal__block jsx-modal-popup jsx-modal-popup_appeal">
        <div class="close close-add-store jsx-modal__close"></div>
        <div class="appeal">
            <div class="appeal__wrapp appeal__wrapp_mw435 appeal__wrapp_m30">
                <div class="little-title">Создание обращения в техподдержку</div>
                <?php $form3 = ActiveForm::begin([
                    'options' => [
                        'class' => 'appeal__form',
                        'data' => ['pjax' => true],
                        'enctype' => 'multipart/form-data',
                    ],
                ]); ?>

                    <?= $form3->field($newTicket, 'user_id')->hiddenInput(['value' => Yii::$app->user->id])
                        ->label(false); ?>
                    <div class="appeal__block">
                        <div class="field">
                            <p class="field__text">тема обращения</p>
                        </div>
                        <?= $form3->field($newTicket, 'subject')->textInput([
                            'class' => 'input choose__name choose__name-appeal',
                            'placeholder' => 'Введите тему'])->label(false); ?>

                        <?= $form3->field($newTicket, 'tickets_text')->textarea([
                            'class' => 'textarea textarea_mb20', 'placeholder' => 'Введите текст сообщения', 'cols' => 30,
                            'rows' => 20])->label(false); ?>

                        <div class="attach">
                            <div class="attach__wrapp-label">
                                <label class="attach__label" id="label-file1" for="file1">
                                    <?= Html::activeFileInput($newTicket, 'ticketFiles[]', [
                                        'multiple' => 'multiple', 'class' => 'left clip-input attach__input',
                                        'id' => 'file1']); ?>
                                    <span class="attach__icon s-di-vertical-m"></span>
                                    <span class="attach__text s-di-vertical-m clip-input-txt">Прикрепить файл</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <?= Html::submitButton('Отправить', ['class' => 'button button_width-200px appeal__button']); ?>
                <?php $form3 = ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
