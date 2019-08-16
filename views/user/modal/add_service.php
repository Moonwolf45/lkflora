<?php

/** @var TYPE_NAME $id_modal */
/** @var TYPE_NAME $modelShop */
/** @var TYPE_NAME $shop_id */
/** @var TYPE_NAME $additions */
/** @var TYPE_NAME $shopsAdditions */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="jsx-modal" data-jsx-modal-id="addService_<?=$id_modal; ?>">
    <div class="jsx-modal__block jsx-modal-popup">
        <div class="close close-add-store jsx-modal__close"></div>
        <div class="popup">
            <div class="popup__wrapp popup__wrapp_add-service">
                <h3 class="popup__title">
                    Добавьте услугу из доступных на Вашем тарифе
                </h3>
                <div class="add-service">
                    <?php $form2 = ActiveForm::begin([
                        'options' => [
                            'class' => 'add-service__form',
                            'data' => ['pjax' => true],
                        ],
                        'action' => Url::to(['/user/shop-edit-service'])
                    ]); ?>
                        <div class="add-service__row">
                            <?= Html::activeHiddenInput($modelShop, 'id',
                                $options = ['label' => false, 'value' => $shop_id]); ?>

                                <?php foreach ($additions as $addition): ?>
                                    <?php $type_currency = ''; if ($addition['type'] == 1) {
                                        $type_currency = ' руб';
                                    } else {
                                        $type_currency = ' руб/мес';
                                    }

                                    if (array_key_exists($shop_id . '_' . $addition['id'], $shopsAdditions)): ?>
                                        <div class="add-service__col">
                                            <div class="add-service__box">
                                                <label class="checkbox">
                                                    <?= Html::activeCheckbox($modelShop, 'addition[' . $addition['id'] . ']',
                                                        $options = ['class' => 'checkbox__checkbox js-add-checkbox-service',
                                                            'label' => false, 'checked' => true]); ?>
                                                    <div class="checkbox__nesting">
                                                        <span class="checkbox__square s-di-ver-top"></span>
                                                        <div class="checkbox__info s-di-ver-top">
                                                            <p class="checkbox__text">
                                                                <?= Html::label($addition['name']); ?>
                                                            </p>
                                                            <span class="checkbox__span">
                                                                <?= Yii::$app->formatter->asDecimal($addition['cost'], 2); ?>
                                                                <?= $type_currency; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </label>
                                                <div class="add-servic__number s-di-ver-top">
                                                    <div class="number js_number">
                                                        <div class="number-minus js_number-minus">-</div>
                                                        <?= Html::activeTextInput($modelShop, 'quantityArr[' . $addition['id'] . ']',
                                                            $options = ['type' => 'number', 'class' => 'number-input goods__number-input js_number-input',
                                                                'value' => $shopsAdditions[$shop_id . '_' . $addition['id']]['quantity']]); ?>
                                                        <div class="number-plus js_number-plus">+</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="add-service__col">
                                            <div class="add-service__box">
                                                <label class="checkbox">
                                                    <?= Html::activeCheckbox($modelShop, 'addition[' . $addition['id'] . ']',
                                                        $options = ['class' => 'checkbox__checkbox js-add-checkbox-service',
                                                            'label' => false, 'value' => $addition['id']]); ?>
                                                    <div class="checkbox__nesting">
                                                        <span class="checkbox__square s-di-ver-top"></span>
                                                        <div class="checkbox__info s-di-ver-top">
                                                            <p class="checkbox__text">
                                                                <?= Html::label($addition['name']); ?>
                                                            </p>
                                                            <span class="checkbox__span">
                                                                <?= Yii::$app->formatter->asDecimal($addition['cost'], 2); ?>
                                                                <?= $type_currency; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </label>
                                                <div class="add-servic__number s-di-ver-top">
                                                    <div class="number js_number">
                                                        <div class="number-minus js_number-minus">-</div>
                                                        <?= Html::activeTextInput($modelShop, 'quantityArr[' . $addition['id'] . ']',
                                                            $options = ['type' => 'number', 'class' => 'number-input goods__number-input js_number-input',
                                                                'value' => 1]); ?>
                                                        <div class="number-plus js_number-plus">+</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                            <?= Html::submitButton('Сохранить', ['class' => 'button button_width-270px add-service__button']); ?>
                        </div>
                    <?php $form2 = ActiveForm::end(); ?>
                    <?=Html::img('@web/images/modal_service.png', ['class' => 'add-service__img']); ?>
                </div>
            </div>
        </div>
    </div>
</div>
