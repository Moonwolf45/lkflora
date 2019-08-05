<?php

use app\models\shops\Shops;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


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
                                        ->label('Техподдержка'); ?>

                                </div>
                            </div>
                            <div class="add-store__col">
                                <div class="add-store__box">
                                    <?= $form->field($modelShop, 'addition[]')->dropDownList(
                                        ArrayHelper::map($additions, 'id', 'name'),
                                        ['prompt' => 'Выберите одну или несколько услуг', 'multiple' => true, 'size' => 1,
                                            'class' => 'jsx-select input choose__name']); ?>

<!--                                    <div class="jsx-select input choose__name">-->
<!--                                        <span class="jsx-select__selected">Выберите одну или несколько</span>-->
<!--                                        <ul class="jsx-select__list choose__list">-->
<!--                                            <li class="choose__item">-->
<!--                                                POS терминал-->
<!--                                            </li>-->
<!--                                            <li class="choose__item">-->
<!--                                                Кассовый апарат-->
<!--                                            </li>-->
<!--                                            <li class="choose__item">-->
<!--                                                Еще что-то-->
<!--                                            </li>-->
<!--                                        </ul>-->
<!--                                    </div>-->
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
