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
            <div class="popup__wrapp popup__wrapp_add-store">
                <h3 class="popup__title">
                    Добавьте услугу из доступных на вашем тарифе
                </h3>
                <div class="add-store">
                    <?php $form2 = ActiveForm::begin([
                        'options' => [
                            'class' => 'add-store__form',
                            'data' => ['pjax' => true],
                        ],
                        'fieldConfig' => [
                            'template' => '<div class="field">{label}{input}{error}{hint}</div>',
                            'labelOptions' => ['class' => 'field__text']
                        ],
                        'action' => Url::to(['/user/shop-edit-service'])
                    ]); ?>

                        <?= $form2->field($modelShop, 'id')->hiddenInput(['value' => $shop_id])
                            ->label(false); ?>

                        <div class="add-store__row">
                            <?php foreach ($additions as $addition): ?>
                                <?php $type_currency = ''; if ($addition['type'] == 1) {
                                    $type_currency = ' руб';
                                } else {
                                    $type_currency = ' руб/мес';
                                }

                                if (array_key_exists($shop_id . '_' . $addition['id'], $shopsAdditions)): ?>
                                    <div class="add-store__col">
                                        <div class="add-store__box" style="margin-left:20px;">
                                            <?= $form2->field($modelShop, 'addition[' . $addition['id'] . ']')->checkbox(['value' => $addition['id'], 'checked' => true,
                                                'label' => $addition['name'] . ' <span class="text-success">(' . Yii::$app->formatter->asDecimal($addition['cost'], 2) . $type_currency . ')</span>']); ?>

                                            <?= $form2->field($modelShop, 'quantityArr[' . $addition['id'] . ']')
                                                ->textInput(['type' => 'number', 'class' => 'input input-checkbox',
                                                    'value' => $shopsAdditions[$shop_id . '_' . $addition['id']]['quantity']]); ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="add-store__col">
                                        <div class="add-store__box" style="margin-left:20px;">
                                            <?= $form2->field($modelShop, 'addition[' . $addition['id'] . ']')->checkbox(['value' => $addition['id'],
                                                'label' => $addition['name'] . ' <span class="text-success">(' . Yii::$app->formatter->asDecimal($addition['cost'], 2) . $type_currency . ')</span>']); ?>

                                            <?= $form2->field($modelShop, 'quantityArr[' . $addition['id'] . ']')
                                                ->textInput(['type' => 'number', 'class' => 'input input-checkbox',
                                                    'value' => 1]); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

                        <?= Html::submitButton('Сохранить', ['class' => 'button button_width-270px add-store__button']); ?>
                    <?php $form2 = ActiveForm::end(); ?>
                    <?=Html::img('@web/images/modal_service.png', ['class' => 'add-store__img']); ?>
                </div>
            </div>
        </div>
    </div>
</div>
