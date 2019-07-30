<?php

use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use kartik\number\NumberControl;
mihaildev\elfinder\Assets::noConflict($this);

/* @var $this yii\web\View */
/* @var $model app\models\Tariff */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tariff-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'cost')->widget(NumberControl::class, [
        'maskedInputOptions' => [
            'allowMinus' => false,
            'groupSeparator' => ' ',
            'radixPoint' => ',',
            'digits' => 2,
        ],
        'displayOptions' => ['class' => 'form-control kv-monospace'],
    ]); ?>

    <?php echo $form->field($model, 'about')->widget(CKEditor::class, [
        'editorOptions' => ElFinder::ckeditorOptions('elfinder', [])
    ]); ?>

    <?= $form->field($model, 'drop')->dropDownList([0 => 'Нет', 1 => 'Да'], [
            'options' => [
                '0' => ['Selected' => true]
            ]
        ]
    ); ?>

    <?= $form->field($model, 'status')->dropDownList([0 => 'Выключен', 1 => 'Включен'], [
            'options' => [
                '1' => ['Selected' => true]
            ]
        ]
    ); ?>

    <?= $form->field($model, 'term')->widget(DatePicker::class); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
