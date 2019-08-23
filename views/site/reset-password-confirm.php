<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin();
echo '<h2 class="user-log__title">Установите новый пароль</h2><br><br>'; ?>
    <div class="field user-log__field">
        <p class="field__text">Новый пароль</p>
        <?= Html::activeInput('password', $model, 'password', ['class' => 'input', 'placeholder' => '']); ?>
    </div>
    <br>
    <div class="field user-log__field">
        <p class="field__text">Подтвердите новый пароль</p>
        <?= Html::activeInput('password', $model, 'password_repeat', ['class' => 'input', 'placeholder' => '']); ?>
    </div>
    <br>
<? echo Html::submitButton('Задать новый пароль', ['class' => 'button user-log__button']);
ActiveForm::end();
