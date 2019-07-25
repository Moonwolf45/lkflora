<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Регистрация';
?>
<?php $form = ActiveForm::begin(['options' => ['id' => 'formRegister', 'class' => 'form-horizontal']]) ?>
<?= $form->field($model, 'email', ['inputOptions' => ['class' => 'registerInput']])->label('Ваш e-mail') ?><br><br>
<?= $form->field($model, 'name', ['inputOptions' => ['class' => 'registerInput']])->label('Ваше имя') ?><br><br>
<?= $form->field($model, 'pass', ['inputOptions' => ['class' => 'registerInput']])->label('Ваш пароль')->input('password') ?>
<br><br>
<?= Html::submitButton('Зарегистрироваться', ['class' => 'btn']) ?>
<?php ActiveForm::end() ?>
<div class="user-log__link-block">
    <p class="user-log__link-block-text">
        Есть логин?
    </p>
    <a href="<?php echo Url::to(['site/index']); ?>" class="user-log__link-block-link">Войти</a>
</div>