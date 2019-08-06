<?php

/** @var TYPE_NAME $id_modal */
/** @var TYPE_NAME $modelShop */
/** @var TYPE_NAME $tariffs */
/** @var TYPE_NAME $tariff_id */
/** @var TYPE_NAME $shop_id */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm; ?>

<div class="jsx-modal" data-jsx-modal-id="tariff_<?=$id_modal; ?>">
    <div class="jsx-modal__block jsx-modal-popup jsx-modal-popup_tariff">
        <div class="close close-add-store jsx-modal__close"></div>
        <div class="tariff">
            <h3 class="popup__title popup__title-tariff">
                Выберите тариф для перехода
            </h3>
            <div class="tariff__wrapp js_tab-parent">
                <ul class="tariff__tab">
                    <?php foreach ($tariffs as $tariff): ?>
                        <?php if($tariff_id != $tariff['id']): ?>
                            <li class="tariff__tab-item js__tab-item" data-id="<?=$tariff['id']; ?>">
                                <div class="version-name">
                                    <h3 class="version-name__title version-name__title_tariff"><?=$tariff['name']; ?></h3>
                                    <p class="version-name__price version-name__price-tab"><?=Yii::$app->formatter->asDecimal($tariff['cost'], 2); ?> руб/мес</p>
                                </div>
                                <div class="tariff__show-hide js__show-hide">
                                    Что входит в тариф?
                                </div>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <ul class="tariff__content">
                    <?php foreach ($tariffs as $tariff): ?>
                        <?php if($tariff_id != $tariff['id']): ?>
                            <li class="tariff__content-item js__content-item">
                                <div class="tariff__content-row">
                                    <?=$tariff['about']; ?>
                                </div>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <?php $form1 = ActiveForm::begin(['options' => ['data' => ['pjax' => true]], 'action' => Url::to(['/user/update-shop'])]); ?>
                    <?= $form1->field($modelShop, 'id')->hiddenInput(['value' => $shop_id])
                        ->label(false); ?>
                    <?= $form1->field($modelShop, 'tariff_id')->hiddenInput(['value' => $tariff_id])
                        ->label(false); ?>
                    <?= Html::submitButton('Перейти', ['class' => 'button button_width-200px tariff__button']); ?>
                <?php $form1 = ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<?php

$script = <<< JS
    $('.js__tab-item').on('click', function(e) {
        var tariff_id = $(this).data('id');
        $('input[name="Shops[tariff_id]"]').val(tariff_id);
    });
JS;
//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, View::POS_READY);

?>
