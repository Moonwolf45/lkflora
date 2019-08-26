<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;


/** @var TYPE_NAME $modelShop */
/** @var TYPE_NAME $tariffs */
/** @var TYPE_NAME $additions */

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
                            <div class="add-store__col add-store__col_w100">
                                <div class="add-store__box">
                                    <div class="field">
                                        <p class="field__text">Доп услуги</p>
                                    </div>
                                    <?= Html::activeDropDownList($modelShop, 'addition[]', ArrayHelper::map($additions, 'id', 'name'), [
                                        'multiple' => 'multiple',
                                        'class' => 'field-select']); ?>
                                </div>
                            </div>
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
    var select = new SlimSelect({
        select: '.field-select',
        showSearch: false,
        placeholder: 'Выберите одну или несколько',
    });
JS;

$this->registerJs($script, View::POS_READY); ?>
