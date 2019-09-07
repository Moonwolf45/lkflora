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

    <?= $form->field($model, 'email')->textInput(['type' => 'email'])->label('E-mail нового пользователя:'); ?>

    <?= $form->field($model, 'phone')->widget(MaskedInput::class, [
        'mask' => '+7 (999) 999-99-99',
    ]); ?>

    <?= $form->field($model, 'doc_num')->textInput()->label('Номер договора'); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
