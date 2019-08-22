<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


/** @var TYPE_NAME $editStore_id */
/** @var TYPE_NAME $modelShop */
/** @var TYPE_NAME $shop_id */
/** @var TYPE_NAME $addressShop */

?>

<div class="jsx-modal" data-jsx-modal-id="editStore_<?=$editStore_id; ?>">
    <div class="jsx-modal__block jsx-modal-popup">
        <div class="close close-add-store jsx-modal__close"></div>
        <div class="popup">
            <div class="popup__wrapp popup__wrapp_add-store">
                <h3 class="popup__title">
                    Добавьте новый магазин
                </h3>
                <div class="add-store">
                    <?php $form4 = ActiveForm::begin([
                        'options' => [
                            'class' => 'add-store__form',
                            'data' => ['pjax' => true],
                        ],
                        'fieldConfig' => [
                            'template' => '<div class="field">{label}{input}{error}{hint}</div>',
                            'labelOptions' => ['class' => 'field__text']
                        ],
                        'action' => Url::to(['/user/edit-shop'])
                    ]); ?>

                    <?= $form4->field($modelShop, 'user_id')->hiddenInput(['value' => Yii::$app->user->id])
                        ->label(false); ?>
                    <?= $form4->field($modelShop, 'id')->hiddenInput(['value' => $shop_id])
                        ->label(false); ?>
                        <div class="add-store__row">
                            <div class="add-store__col">
                                <div class="add-store__box">
                                    <?= $form4->field($modelShop, 'address')->textInput([
                                        'class' => 'input input-add-store', 'value' => $addressShop
                                    ]); ?>
                                </div>
                            </div>
                        </div>

                        <?= Html::submitButton('Изменить', ['class' => 'button button_width-270px add-store__button']); ?>
                    <?php $form4 = ActiveForm::end(); ?>

                    <?php $form5 = ActiveForm::begin(['options' => ['class' => 'add-store__form',
                        'data' => ['pjax' => true]], 'action' => Url::to(['/user/delete-shop'])]); ?>
                        <?= $form5->field($modelShop, 'id')->hiddenInput(['value' => $shop_id])
                            ->label(false); ?>
                        <?= Html::submitButton('Удалить магазин', ['class' => 'button button_width-270px add-store__button drop_shop']); ?>
                    <?php $form5 = ActiveForm::end(); ?>
                    <?=Html::img('@web/images/add-store-photo.png', ['class' => 'add-store__img']); ?>
                </div>
            </div>
        </div>
    </div>
</div>
