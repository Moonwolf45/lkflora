<?php

use app\models\tariff\Tariff;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use kartik\number\NumberControl;
mihaildev\elfinder\Assets::noConflict($this);

/* @var $this yii\web\View */
/* @var $model app\models\tariff\Tariff */
/* @var $form yii\widgets\ActiveForm */
/* @var $additions app\models\addition\Addition */
?>

<div class="tariff-form">
    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]); ?>

        <?= $form->field($model, 'cost')->widget(NumberControl::class, [
            'maskedInputOptions' => ['allowMinus' => false, 'groupSeparator' => ' ', 'radixPoint' => ',', 'digits' => 2],
            'displayOptions' => ['class' => 'form-control kv-monospace'],
        ])->label('Стоимость обслуживания (ежемесячно)'); ?>

        <?php echo $form->field($model, 'about')->widget(CKEditor::class, [
            'editorOptions' => ElFinder::ckeditorOptions('elfinder', [])
        ]); ?>

        <?= $form->field($model, 'drop')->dropDownList(Tariff::getDrop(), [
                'options' => [
                    '0' => ['Selected' => true]
                ]
            ]
        ); ?>

        <?= $form->field($model, 'status')->dropDownList(Tariff::getStatus(), [
                'options' => [
                    '1' => ['Selected' => true]
                ]
            ]
        ); ?>

        <?= $form->field($model, 'maximum')->checkbox(); ?>

        <?= $form->field($model, 'term')->textInput(['maxlength' => true]); ?>

        <div class="row block_service">
            <h4>Разршенные услуги</h4>
            <div class="block_resolutionService">
                <?php if(!empty($model->tariffAdditionQty)): ?>
                    <?php foreach ($model->tariffAdditionQty as $taQ): ?>
                        <div>
                            <?= $form->field($model, 'resolutionService[]')->dropDownList(
                                ArrayHelper::map($additions, 'id', 'name'), [
                                    'prompt' => 'Выберите доп. услуги котоые можно подключать в данном тарифе',
                                    'options' => [$taQ->addition_id => ['selected' => true]]
                                ]
                            ); ?>

                            <?= $form->field($model, 'resolutionServiceQuantity[]')->textInput([
                                'type' => 'number',
                                'value' => $taQ->status_con
                            ]); ?>
                            <button type="button" class="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div>
                        <?= $form->field($model, 'resolutionService[]')->dropDownList(
                            ArrayHelper::map($additions, 'id', 'name'), [
                                'prompt' => 'Выберите доп. услуги котоые можно подключать в данном тарифе',
                            ]
                        ); ?>

                        <?= $form->field($model, 'resolutionServiceQuantity[]')->textInput([
                                'type' => 'number'
                        ]); ?>
                    </div>
                <?php endif; ?>
            </div>
            <button type="button" class="btn btn-success add_res_ser">+</button>
        </div>

        <div class="row block_service">
            <h4>Бесплатные услуги</h4>
            <div class="block_connectedService">
                <?php if(!empty($model->tariffAddition)): ?>
                    <?php foreach ($model->tariffAddition as $ta): ?>
                        <div>
                            <?= $form->field($model, 'connectedService[]')->dropDownList(
                                ArrayHelper::map($additions, 'id', 'name'), [
                                    'prompt' => 'Выберите доп. услуги которые уже подключены в данном тарифе',
                                    'options' => [$ta->addition_id => ['selected' => true]]
                                ]
                            ); ?>

                            <?= $form->field($model, 'connectedServiceQuantity[]')->textInput([
                                'type' => 'number',
                                'value' => $ta->quantity
                            ]); ?>
                            <button type="button" class="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div>
                        <?= $form->field($model, 'connectedService[]')->dropDownList(
                            ArrayHelper::map($additions, 'id', 'name'), [
                                'prompt' => 'Выберите доп. услуги которые уже подключены в данном тарифе',
                            ]
                        ); ?>

                        <?= $form->field($model, 'connectedServiceQuantity[]')->textInput([
                            'type' => 'number'
                        ]); ?>
                    </div>
                <?php endif; ?>
            </div>
            <button type="button" class="btn btn-success add_con_ser">+</button>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']); ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$script = <<< JS
    $('.add_res_ser').on('click', function(e) {
        let new_data = $('.block_resolutionService').children()[0].cloneNode(true);
        new_data.children[0].children[1].value = '';
        new_data.children[1].children[1].value = 0;
        $('.block_resolutionService').append(new_data);
    });

    $('.add_con_ser').on('click', function(e) {
        let new_data = $('.block_connectedService').children()[0].cloneNode(true);
        new_data.children[0].children[1].value = '';
        new_data.children[1].children[1].value = 0;
        $('.block_connectedService').append(new_data);
    });
    
    $('div').on('click', '.close', function(e) {
        $(this).parent().remove();
    });
JS;

$this->registerJs($script, View::POS_READY); ?>
