<?php

use app\models\db\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\shops\ShopsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shops-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id'); ?>

    <?= $form->field($model, 'name'); ?>

    <?= $form->field($model, 'address'); ?>

    <?php echo $form->field($model, 'user_id')->dropDownList(ArrayHelper::map(User::find()->all(), 'id',
        'company_name'), ['prompt' => 'Выберите компанию']); ?>

    <div class="form-group">
        <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Сбростиь', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
