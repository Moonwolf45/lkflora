<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin();
echo "<h2 class=\"user-log__title\">Установите новый пароль</h2><br><br>";
echo Html::activeInput('password', $model, 'password', ['class' => 'input', 'placeholder' => '']);
echo '<br>';
echo Html::activeInput('password', $model, 'password_repeat', ['class' => 'input', 'placeholder' => '']);
echo '<br>';
echo Html::submitButton('Задать новый пароль', ['class' => 'button user-log__button']);
ActiveForm::end();