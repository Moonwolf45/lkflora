<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @return string
 * @var $model app\models\db\User
 * @var $form  yii\widgets\ActiveForm
 * @var $this  yii\web\View
 */

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <label for="user-email">E-mail нового пользователя:</label>
    <?= Html::activeInput('text', $model, 'email', ['class' => 'form-control']) ?>

    <br>

    <label for="user-doc_num">Номер договора:</label>
    <?= Html::activeInput('text', $model, 'doc_num', ['class' => 'form-control']) ?>

    <br>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
