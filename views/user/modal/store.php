<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;


/** @var $modelShop app\models\shops\Shops */
/** @var $tariffs app\models\tariff\Tariff */

?>

<div class="jsx-modal" data-jsx-modal-id="store">
    <div class="jsx-modal__block jsx-modal-popup">
        <div class="close close-add-store jsx-modal__close"></div>
        <div class="popup">
            <div class="popup__wrapp popup__wrapp_add-store">
                <h3 class="popup__title">
                    Добавьте новый магазин
                </h3>
                <div class="add-store">
                    <?php $form = ActiveForm::begin([
                        'options' => [
                            'class' => 'add-store__form',
                            'data' => ['pjax' => true],
                        ],
                        'fieldConfig' => [
                            'template' => '<div class="field">{label}{input}{error}{hint}</div>',
                            'labelOptions' => ['class' => 'field__text']
                        ],
                    ]); ?>

                    <?= $form->field($modelShop, 'user_id')->hiddenInput(['value' => Yii::$app->user->id])
                        ->label(false); ?>
                        <div class="add-store__row">
                            <div class="add-store__col">
                                <div class="add-store__box">
                                    <?= $form->field($modelShop, 'address')->textInput([
                                        'class' => 'input input-add-store'
                                    ]); ?>
                                </div>
                            </div>
                            <div class="add-store__col">
                                <div class="add-store__box">
                                    <?= $form->field($modelShop, 'tariff_id')->dropDownList(
                                        ArrayHelper::map($tariffs, 'id', 'name'),
                                        ['prompt' => 'Выберите тариф', 'class' => 'jsx-select input choose__name'])
                                        ->label('Тариф'); ?>
                                </div>
                            </div>
                            <?php $all_addition = []; foreach ($tariffs as $key => $tariff): ?>
                                <?php $all_addition[$tariff['id']] = array_merge($tariff['tariffAdditionQty'], $tariff['tariffAddition'])?>
                                <?php if ($key == 0): ?>
                                    <div class="add-store__col add-store__col_w100 addition_block" id="tariff_<?= $tariff['id']; ?>">
                                        <div class="add-store__box">
                                            <div class="field">
                                                <p class="field__text">Доп услуги</p>
                                            </div>
                                            <?= Html::activeDropDownList($modelShop, 'addition[]',
                                                ArrayHelper::map($all_addition[$tariff['id']], 'addition_id', 'addition.name'), [
                                                    'multiple' => 'multiple',
                                                    'class' => 'field-select_' . $tariff['id']]); ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="add-store__col add-store__col_w100 addition_block" id="tariff_<?= $tariff['id']; ?>" style="display:none;">
                                        <div class="add-store__box">
                                            <div class="field">
                                                <p class="field__text">Доп услуги</p>
                                            </div>
                                            <?= Html::activeDropDownList($modelShop, 'addition[]',
                                                ArrayHelper::map($all_addition[$tariff['id']], 'addition_id', 'addition.name'), [
                                                    'multiple' => 'multiple',
                                                    'class' => 'field-select_' . $tariff['id']]); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

                        <?= Html::submitButton('Отправить', ['class' => 'button button_width-270px add-store__button']); ?>
                    <?php $form = ActiveForm::end(); ?>
                    <?=Html::img('@web/images/add-store-photo.png', ['class' => 'add-store__img']); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$script = <<< JS
    $('.addition_block').each(function(index, value) {
        let tariff_id = $(this).attr('id');
        let idArr = tariff_id.split('_');
        let select = new SlimSelect({
            select: '.field-select_' + idArr[1],
            showSearch: false,
            placeholder: 'Выберите одну или несколько',
        });   
    });

    $('select[name="Shops[tariff_id]"]').on('change', function(e) {
        let id = $(this).val();
        
        $('.addition_block').each(function(index, value) {
            if ('tariff_' + id == $(this).attr('id')) {
                $(this).css({'display':'block'});
            } else {
                $(this).css({'display':'none'});
            }
        });
    });
JS;

$this->registerJs($script, View::POS_READY); ?>
