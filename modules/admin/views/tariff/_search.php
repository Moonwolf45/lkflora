<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\tariff\TariffSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tariff-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?=$form->field($model, 'id'); ?>

    <?=$form->field($model, 'name'); ?>

    <?=$form->field($model, 'cost'); ?>

    <?=$form->field($model, 'about'); ?>

    <?=$form->field($model, 'drop'); ?>

    <?=$form->field($model, 'status'); ?>

    <?=$form->field($model, 'maximum'); ?>

    <?=$form->field($model, 'term'); ?>

    <div class="form-group">
        <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']); ?>
        <?= Html::resetButton('Сбросить', ['class' => 'btn btn-outline-secondary']); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
