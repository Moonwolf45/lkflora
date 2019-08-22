<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Восстановление пароля';

$form = ActiveForm::begin(); ?>
    <div class="field user-log__field">
        <p class="field__text">Ваш email</p>
        <?= $form->field($model, 'email', ['inputOptions' => ['class' => 'input']])->label(false); ?>
    </div>
<? echo '<br>';
echo Html::submitButton('Восстановить', ['class' => 'button user-log__button']);
ActiveForm::end();

echo "<br><br><div style='text-align: center'><a href=" . Url::to(['site/index']) . " class='user-log__link_gray'>Авторизоваться</a></div>";
