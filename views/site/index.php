<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Авторизация';
?>
<?php $form = ActiveForm::begin() ?>
<div class="field user-log__field">
    <p class="field__text">Ваш email</p>
    <?= $form->field($model, 'email', [
        'inputOptions' => ['class' => 'input']
    ])->label(''); ?>
</div>
<div class="field user-log__field">
    <div class="field__justi">
        <p class="field__text">Ваш Пароль</p>
        <a href="<?php echo Url::to(['site/reset-password']); ?>" class="field__link">Забыли пароль?</a>
    </div>
    <?= $form->field($model, 'password', [
        'inputOptions' => ['class' => 'input'],
    ])->label('')->input('password') ?>
</div>
<?= Html::submitButton('Войти', ['class' => 'button user-log__button']) ?>
<?php ActiveForm::end() ?>
<!--<div class="user-log__link-block">-->
<!--    <p class="user-log__link-block-text">-->
<!--        Вы еще не с нами?-->
<!--    </p>-->
<!--    <a href="--><?php //echo Url::to(['site/registration']); ?><!--" class="user-log__link-block-link">Регистрация</a>-->
<!--</div>-->
