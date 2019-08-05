<?php

use app\models\db\User;
use app\models\tariff\Tariff;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\shops\Shops */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shops-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'tariff_id')->dropDownList(ArrayHelper::map(Tariff::find()->all(), 'id',
        'name'), ['prompt' => 'Выберите тариф']); ?>

    <?= $form->field($model, 'user_id')->dropDownList(ArrayHelper::map(User::find()->all(), 'id',
        'company_name'), ['prompt' => 'Выберите бренд']); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
