<?php

/** @var $id_modal */
/** @var $modelShop app\models\shops\Shops */
/** @var $tariffs app\models\tariff\Tariff */
/** @var $tariff_id */
/** @var $tariff_drop */
/** @var $shop_id */
/** @var $shopsAdditions */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

$tariff_drop = $tariffs[$tariff_id]['drop'];

$additions_tariff = [];
foreach ($tariffs as $tariff) {
    if ($tariff_id != $tariff['id']) {
        $tAQ_keys = array_keys($tariff['tariffAdditionQty']);
        $tA_keys = array_keys($tariff['tariffAddition']);

        foreach ($tariff['tariffAddition'] as $tA) {
            if (in_array($tA['addition_id'], $tAQ_keys)) {
                $additions_tariff[$tariff['id']][$tA['addition_id']] = $tA;
                $additions_tariff[$tariff['id']][$tA['addition_id']]['status_con'] = $tariffs[$tariff['id']]['tariffAdditionQty'][$tA['addition_id']]['status_con'];
            } else {
                $additions_tariff[$tariff['id']][$tA['addition_id']] = $tA;
            }
        }

        foreach ($tariff['tariffAdditionQty'] as $tAQ) {
            if (in_array($tAQ['addition_id'], $tA_keys)) {
                $additions_tariff[$tariff['id']][$tAQ['addition_id']] = $tAQ;
                $additions_tariff[$tariff['id']][$tAQ['addition_id']]['quantity'] = $tariffs[$tariff['id']]['tariffAddition'][$tAQ['addition_id']]['quantity'];
            } else {
                $additions_tariff[$tariff['id']][$tAQ['addition_id']] = $tAQ;
            }
        }
    }
}

$shop_A = [];
foreach ($shopsAdditions as $sA) {
    $shop_A[$sA['addition_id']] = $sA;
}

$error = [];
foreach ($additions_tariff as $key_tariff => $a_t) {
    $error[$key_tariff]['status'] = false;
    $error[$key_tariff]['text'] = '';
    $i = 0;
    foreach ($shop_A as $key => $addition) {
        if (!in_array($key, array_keys($a_t))) {
            $error[$key_tariff]['text'] .= $addition['addition']['name'] . ', ';
            $error[$key_tariff]['status'] = true;
            $i++;
        }
    }

    if ($error[$key_tariff]['status']) {
        $error[$key_tariff]['text'] = mb_strimwidth($error[$key_tariff]['text'], 0, iconv_strlen($error[$key_tariff]['text']) - 2);

        if ($i == 1) {
            $error[$key_tariff]['text'] .= ' - Данная услуга будет отключена при переходе на выбранный тариф';
        } else {
            $error[$key_tariff]['text'] .= ' - Данные услуги будут отключены при переходе на выбранный тариф';
        }
    }
}

?>

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
                        <?php if ($tariff_drop): ?>
                            <?php if($tariff_id < $tariff['id']): ?>
                                <li class="tariff__tab-item js__tab-item" data-id="<?=$tariff['id']; ?>" data-name="<?=$tariff['name']; ?>">
                                    <div class="version-name">
                                        <h3 class="version-name__title version-name__title_tariff"><?=$tariff['name']; ?></h3>
                                        <p class="version-name__price version-name__price-tab"><?=Yii::$app->formatter
                                                ->asDecimal($tariff['cost'], 2); ?>
                                            <?php if($tariff['term'] == ''): ?>
                                                руб/мес
                                            <?php else: ?>
                                                руб
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="tariff__show-hide js__show-hide">
                                        Что входит в тариф?
                                    </div>
                                </li>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if($tariff_id != $tariff['id']): ?>
                                <li class="tariff__tab-item js__tab-item" data-id="<?=$tariff['id']; ?>" data-name="<?=$tariff['name']; ?>">
                                    <div class="version-name">
                                        <h3 class="version-name__title version-name__title_tariff"><?=$tariff['name']; ?></h3>
                                        <p class="version-name__price version-name__price-tab"><?=Yii::$app->formatter
                                                ->asDecimal($tariff['cost'], 2); ?>
                                            <?php if($tariff['term'] == ''): ?>
                                                руб/мес
                                            <?php else: ?>
                                                руб
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="tariff__show-hide js__show-hide">
                                        Что входит в тариф?
                                    </div>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <ul class="tariff__content">
                    <?php foreach ($tariffs as $tariff): ?>
                        <?php if ($tariff_drop): ?>
                            <?php if ($tariff_id < $tariff['id']): ?>
                                <li class="tariff__content-item js__content-item">
                                    <div class="tariff__content-row">
                                        <?=$tariff['about']; ?>
                                    </div>
                                </li>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if($tariff_id != $tariff['id']): ?>
                                <li class="tariff__content-item js__content-item">
                                    <div class="tariff__content-row">
                                        <?=$tariff['about']; ?>
                                    </div>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>

                <div class="alert tariff_change" style="display:none;">
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Внимание</h4>
                        <p></p>
                    </div>
                </div>

                <?php foreach ($error as $key => $string): ?>
                    <?php if ($string['status']): ?>
                        <div class="alert edit_addition" id="string_<?= $key; ?>" style="display:none;">
                            <div class="alert alert-warning" role="alert">
                                <h4 class="alert-heading">Важно!</h4>
                                <p><?= $string['text']; ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>

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
        let tariff_id = $(this).data('id');
        let tariff_name = $(this).data('name');
        $('input[name="Shops[tariff_id]"]').val(tariff_id);
        
        $('.tariff_change div p').html('Вы собираетесь перейти на тариф - ' + tariff_name);
        $('.tariff_change').css({"display":"block"});
        
        $('.edit_addition').css({"display":"none"});
        $('#string_' + tariff_id).css({"display":"block"});
    });
JS;
//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, View::POS_READY);

?>
