<?php

use app\models\addition\Addition;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use kartik\number\NumberControl;
mihaildev\elfinder\Assets::noConflict($this);

/* @var $this yii\web\View */
/* @var $model app\models\addition\Addition */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="addition-form">

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

    <?= $form->field($model, 'type')->dropDownList([Addition::TYPE_NOT_REPEAT => 'Фиксированный',
        Addition::TYPE_REPEAT => 'Ежемесячный'], [
            'options' => [
                '1' => ['Selected' => true]
            ]
        ]
    ); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
