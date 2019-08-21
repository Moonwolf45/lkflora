<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @return string
 * @var $model app\models\db\User
 * @var $form  yii\widgets\ActiveForm
 * @var $this  yii\web\View
 */

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <label for="user-email">E-mail нового пользователя:</label>
    <?= Html::activeTextInput($model, 'email', ['class' => 'form-control']); ?>

    <br>

    <?= $form->field($model, 'phone')->widget(MaskedInput::class, [
        'mask' => '+7 (999) 999-99-99',
    ]); ?>

    <br>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
