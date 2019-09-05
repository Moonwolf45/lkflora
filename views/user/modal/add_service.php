<?php

/** @var $id_modal */
/** @var $modelShop app\models\shops\Shops */
/** @var $shop_id */
/** @var $tariff_id */
/** @var $tariffs app\models\tariff\Tariff */
/** @var $shopsAdditions */

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

                            <?= Html::activeHiddenInput($modelShop, 'tariff_id',
                                $options = ['label' => false, 'value' => $tariff_id]); ?>

                                <?php $tAQ_keys = array_keys($tariffs[$tariff_id]['tariffAdditionQty']);
                                    $tA_keys = array_keys($tariffs[$tariff_id]['tariffAddition']);

                                $tariff_additions = [];
                                foreach ($tariffs[$tariff_id]['tariffAddition'] as $tA) {
                                    if (in_array($tA['addition_id'], $tAQ_keys)) {
                                        if ($tA['quantity'] > $tariffs[$tariff_id]['tariffAdditionQty'][$tA['addition_id']]['status_con']) {
                                            $tariff_additions[$tA['addition_id']] = $tA;
                                        } else {
                                            $tariff_additions[$tA['addition_id']] = $tariffs[$tariff_id]['tariffAdditionQty'][$tA['addition_id']];
                                        }
                                    } else {
                                        $tariff_additions[$tA['addition_id']] = $tA;
                                    }
                                }

                                foreach ($tariffs[$tariff_id]['tariffAdditionQty'] as $tAQ) {
                                    if (in_array($tAQ['addition_id'], $tA_keys)) {
                                        if ($tAQ['status_con'] > $tariffs[$tariff_id]['tariffAddition'][$tAQ['addition_id']]['quantity']) {
                                            $tariff_additions[$tAQ['addition_id']] = $tAQ;
                                        } else {
                                            $tariff_additions[$tAQ['addition_id']] = $tariffs[$tariff_id]['tariffAddition'][$tAQ['addition_id']];
                                        }
                                    } else {
                                        $tariff_additions[$tAQ['addition_id']] = $tAQ;
                                    }
                                }

                                $free_services = [];
                                foreach ($tariffs[$tariff_id]['tariffAddition'] as $tA) {
                                    $free_services[$tA['addition']['id']]['name'] = $tA['addition']['name'];
                                    $free_services[$tA['addition']['id']]['quantity'] = $tA['quantity'];
                                }

                                foreach ($tariff_additions as $tAQ): ?>
                                    <?php $type_currency = ''; if ($tAQ['addition']['type'] == 1) {
                                        $type_currency = ' руб';
                                    } else {
                                        $type_currency = ' руб/мес';
                                    }

                                    if (array_key_exists('status_con', $tAQ)) {
                                        if ($tAQ['status_con'] == 0) {
                                            $max = 999;
                                        } else {
                                            $max = $tAQ['status_con'];
                                        }
                                    }

                                    if (array_key_exists('quantity', $tAQ)) {
                                        if ($tAQ['quantity'] == 0) {
                                            $max = 999;
                                        } else {
                                            $max = $tAQ['quantity'];
                                        }
                                    }

                                    if (array_key_exists($shop_id . '_' . $tAQ['addition']['id'], $shopsAdditions)): ?>
                                        <div class="add-service__col">
                                            <div class="add-service__box">
                                                <label class="checkbox">
                                                    <?= Html::activeCheckbox($modelShop, 'addition[' . $tAQ['addition']['id'] . ']',
                                                        $options = ['class' => 'checkbox__checkbox js-add-checkbox-service',
                                                            'label' => false, 'checked' => true]); ?>
                                                    <div class="checkbox__nesting">
                                                        <span class="checkbox__square s-di-ver-top"></span>
                                                        <div class="checkbox__info s-di-ver-top">
                                                            <p class="checkbox__text">
                                                                <?= Html::label($tAQ['addition']['name']); ?>
                                                            </p>
                                                            <span class="checkbox__span">
                                                                <?= Yii::$app->formatter->asDecimal($tAQ['addition']['cost'], 2); ?>
                                                                <?= $type_currency; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </label>
                                                <div class="add-servic__number s-di-ver-top">
                                                    <div class="number js_number">
                                                        <div class="number-minus js_number-minus">-</div>
                                                        <div>
                                                            <?= Html::activeTextInput($modelShop, 'quantityArr[' . $tAQ['addition']['id'] . ']',
                                                                $options = ['type' => 'number', 'class' => 'number-input js_number-input',
                                                                    'value' => $shopsAdditions[$shop_id . '_' . $tAQ['addition']['id']]['quantity'],
                                                                    'min' => 1, 'max' => $max]); ?>

                                                            <?php if (array_key_exists($tAQ['addition']['id'], $free_services)) {
                                                                $free_services[$tAQ['addition']['id']]['quantity'] -= $shopsAdditions[$shop_id . '_' . $tAQ['addition']['id']]['quantity'];
                                                            } ?>
                                                        </div>
                                                        <div class="number-plus js_number-plus">+</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="add-service__col">
                                            <div class="add-service__box">
                                                <label class="checkbox">
                                                    <?= Html::activeCheckbox($modelShop, 'addition[' . $tAQ['addition']['id'] . ']',
                                                        $options = ['class' => 'checkbox__checkbox js-add-checkbox-service',
                                                            'label' => false, 'value' => $tAQ['addition']['id']]); ?>
                                                    <div class="checkbox__nesting">
                                                        <span class="checkbox__square s-di-ver-top"></span>
                                                        <div class="checkbox__info s-di-ver-top">
                                                            <p class="checkbox__text">
                                                                <?= Html::label($tAQ['addition']['name']); ?>
                                                            </p>
                                                            <span class="checkbox__span">
                                                                <?= Yii::$app->formatter->asDecimal($tAQ['addition']['cost'], 2); ?>
                                                                <?= $type_currency; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </label>
                                                <div class="add-servic__number s-di-ver-top">
                                                    <div class="number js_number">
                                                        <div class="number-minus js_number-minus">-</div>
                                                        <div>
                                                            <?= Html::activeTextInput($modelShop, 'quantityArr[' . $tAQ['addition']['id'] . ']',
                                                                $options = ['type' => 'number', 'class' => 'number-input js_number-input',
                                                                    'value' => 1, 'min' => 1, 'max' => $max]); ?>
                                                        </div>
                                                        <div class="number-plus js_number-plus">+</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                                <?php if(!empty($free_services)): ?>
                                    <div class="alert">
                                        <div class="alert alert-success" role="alert">
                                            <h4 class="alert-heading">Внимание</h4>
                                            <p>На вашем тарифе доступны бесплатные услуги.</p>
                                            <hr>
                                            <?php foreach ($free_services as $fs): ?>
                                                <?php if ($fs['quantity'] > 0): ?>
                                                    <p class="mb-0">
                                                        <b><?php echo $fs['name']; ?></b> в количестве
                                                        <b><?php echo $fs['quantity']; ?></b>
                                                    </p>
                                                <?else: ?>
                                                    <p class="mb-0">
                                                        <b><?php echo $fs['name']; ?></b> в количестве
                                                        <b>0</b>
                                                    </p>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                            <?= Html::submitButton('Сохранить', ['class' => 'button button_width-270px add-service__button']); ?>
                        </div>
                    <?php $form2 = ActiveForm::end(); ?>
                    <?=Html::img('@web/images/modal_service.png', ['class' => 'add-service__img']); ?>
                </div>
            </div>
        </div>
    </div>
</div>
