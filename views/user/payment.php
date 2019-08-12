<?php

use app\models\payments\Payments;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/** @var TYPE_NAME $modelPaid */
/** @var TYPE_NAME $d */
/** @var TYPE_NAME $i */

$this->title = 'Детализация баланса'; ?>

<div class="content">
    <h2 class="content__title">Баланс</h2>
    <div class="content__row">
        <div class="content__col-12">
            <div class="content__box">
                <div class="payment">
                    <div class="payment__row">
                        <div class="payment__col">
                            <div class="little-title">Ваш баланс</div>
                            <p class="payment__balance">
                                <?=Yii::$app->formatter->asDecimal(Yii::$app->user->identity->balance, 2); ?>
                                <span class="payment__balance-span">руб</span>
                            </p>
                        </div>
                        <div class="payment__col payment__col_mobile">
                            <div class="little-title">Пополнить баланс</div>
                            <div class="field payment__field">
                                <input type="number" name="paid" class="input" placeholder="Введите сумму для пополнения">
                                <div class="help-block"></div>
                            </div>
                            <div class="bill-btn">
                                <div class="bill-btn__box">
                                    <div class="bill-btn__icon">
                                        <?=Html::img('@web/images/icon/icon-bill-pdf.svg'); ?>
                                    </div>
                                    <?php $form3 = ActiveForm::begin(['action' => Url::to(['/user/save-pdf']), 'id' => 'f3']); ?>
                                        <?= $form3->field($modelPaid, 'newPaid')->hiddenInput(['value' => 0])
                                            ->label(false)->error(false); ?>
                                        <?= Html::submitButton('Выставить счёт', ['class' => 'bill-btn__link']); ?>
                                    <?php $form3 = ActiveForm::end(); ?>
                                </div>

                                <div class="bill-btn__box">
                                    <div class="bill-btn__icon">
                                        <?=Html::img('@web/images/icon/icon-bill-cards.svg'); ?>
                                    </div>
                                    <?php $form4 = ActiveForm::begin(['action' => Url::to(['/user/paid-card']), 'id' => 'f4']); ?>
                                        <?= $form4->field($modelPaid, 'newPaid')->hiddenInput(['value' => 0])
                                            ->label(false)->error(false); ?>
                                        <?= Html::submitButton('Оплатить картой', ['class' => 'bill-btn__link']); ?>
                                    <?php $form4 = ActiveForm::end(); ?>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="payment__tab-mobile js_tab-parent">
                        <ul class="payment__tab">
                            <li class="payment__tab-item js__tab-item active">
                                <p class="sub-title sub-title-mobile">
                                    счета
                                </p>
                            </li>
                            <li class="payment__tab-item js__tab-item">
                                <p class="sub-title sub-title-mobile">
                                    Оплаты
                                </p>
                            </li>
                            <li class="payment__tab-item js__tab-item">
                                <p class="sub-title sub-title-mobile">
                                    детализация
                                </p>
                            </li>
                        </ul>

                        <ul class="payment__content">
                            <li class="payment__content-item js__content-item">
                                <?php Pjax::begin(); ?>
                                    <table class="table">
                                        <?php if(!empty($invoice)): ?>
                                            <?php foreach($invoice as $inv): ?>
                                                <tr class="table__row">
                                                    <td class="table__col">
                                                        <p class="table__text">Счет №
                                                            <span class="s-medium"><?= $inv['invoice_number']; ?></span>
                                                            от <?=Yii::$app->formatter->asDate($inv['invoice_date']); ?>
                                                        </p>
                                                    </td>
                                                    <td class="table__col">
                                                        <p>
                                                            <span class="s-medium">
                                                                <?=Yii::$app->formatter->asDecimal($inv['amount'], 2); ?>
                                                            </span> руб.
                                                        </p>
                                                    </td>
                                                    <td class="table__col">
                                                        <?php if ($inv['status'] == Payments::STATUS_PAID): ?>
                                                            <p class="table__status table__status_on">Оплачен</p>
                                                        <?php else: ?>
                                                            <p class="table__status table__status_off">Не оплачен</p>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr class="table__row">
                                                <td colspan="3" class="empty_res">
                                                    Пополнений еще не было
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </table>

                                    <a href="<?=Url::to(['/user/payment-invoice', 'i' => $i++]); ?>" class="show-more">
                                        Показать ещё
                                    </a>
                                <?php Pjax::end(); ?>
                            </li>

                            <li class="payment__content-item js__content-item">
                                <?php Pjax::begin(); ?>
                                    <ul class="fee-history__list fee-history__list_history">
                                        <?php if(!empty($deposit)): ?>
                                            <?php foreach($deposit as $dep): ?>
                                                <li class="fee-history__item">
                                                    <div class="fee-history__col">
                                                        <p><?=Yii::$app->formatter->asDate($dep['date']); ?></p>
                                                    </div>
                                                    <div class="fee-history__col">
                                                        <p>
                                                            <span class="s-medium">
                                                                <?=Yii::$app->formatter->asDecimal($dep['amount'], 2); ?>
                                                            </span> руб.
                                                        </p>
                                                    </div>
                                                    <div class="fee-history__col">
                                                        <?php if ($dep['way'] == 0): ?>
                                                            <p>Карта</p>
                                                        <?php else: ?>
                                                            <p>Счёт</p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="fee-history__col">
                                                        <p class="s-fz12px">
                                                            <?php if ($dep['way'] == 1): ?>
                                                                <a href="" class="fee-history__link">скачать акт</a>
                                                            <?php endif; ?>
                                                        </p>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li class="fee-history__item">
                                                <div class="empty_res">
                                                    Пополнений еще не было
                                                </div>
                                            </li>
                                        <?php endif; ?>
                                    </ul>

                                    <a href="<?=Url::to(['/user/payment-deposit', 'd' => $d++]); ?>" class="show-more">
                                        Показать ещё
                                    </a>
                                <?php Pjax::end(); ?>
                            </li>

                            <li class="payment__content-item js__content-item">
                                <ul class="fee-history__list">
                                    <?php if(!empty($payments)): ?>
                                        <?php foreach($payments as $payment): ?>
                                            <li class="fee-history__item">
                                                <div class="fee-history__col">
                                                    <p><?=Yii::$app->formatter->asDate($payment['date']); ?></p>
                                                </div>
                                                <div class="fee-history__col">
                                                    <p>
                                                        <span class="s-medium">
                                                            <?=Yii::$app->formatter->asDecimal($payment['amount'], 2); ?>
                                                        </span> руб.
                                                    </p>
                                                </div>
                                                <div class="fee-history__col">
                                                    <p class="s-fz12px"><?= $payment['description']; ?></p>
                                                </div>
                                                <div class="fee-history__col">
                                                    <?php if($payment['shop']): ?>
                                                        <p class="s-fz12px"><?= $payment['shop']['address'];?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="fee-history__item">
                                            <div class="empty_res">
                                                Списаний еще не было
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <div class="payment__row payment__row-desctop">
                        <div class="payment__col">
                            <div class="check-status">
                                <p class="sub-title">
                                    Созданные счета
                                </p>
                                <?php Pjax::begin(); ?>
                                    <div class="check-status__wrapp">
                                        <?php if(!empty($invoice)): ?>
                                            <?php foreach($invoice as $inv): ?>
                                                <div class="check-status__block">
                                                    <p class="check-status__text">Счет №
                                                        <span class="check-status__span"><?= $inv['invoice_number']; ?></span>
                                                        от <?=Yii::$app->formatter->asDate($inv['invoice_date']); ?>
                                                    </p>
                                                    <p class="check-status__text">
                                                        <span class="check-status__span">
                                                            <?=Yii::$app->formatter->asDecimal($inv['amount'], 2); ?>
                                                        </span> руб.
                                                    </p>
                                                    <?php if ($inv['status'] == Payments::STATUS_PAID): ?>
                                                        <p class="check-status__condition check-status__condition_on">Оплачен</p>
                                                    <?php else: ?>
                                                        <p class="check-status__condition check-status__condition_off">Не оплачен</p>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="check-status__block">
                                                <div class="empty_res">
                                                    Пополнений еще не было
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <a href="<?=Url::to(['/user/payment-invoice', 'i' => $i++]); ?>" class="show-more">
                                        Показать ещё
                                    </a>
                                <?php Pjax::end(); ?>
                            </div>

                            <div class="fee-history fee-history_pt60">
                                <p class="sub-title">
                                    История оплат
                                </p>
                                <?php Pjax::begin(); ?>
                                    <ul class="fee-history__list fee-history__list_history">
                                        <?php if(!empty($deposit)): ?>
                                            <?php foreach($deposit as $dep): ?>
                                                <li class="fee-history__item">
                                                    <div class="fee-history__col">
                                                        <p><?=Yii::$app->formatter->asDate($dep['date']); ?></p>
                                                    </div>
                                                    <div class="fee-history__col">
                                                        <p>
                                                            <span class="s-medium">
                                                                <?=Yii::$app->formatter->asDecimal($dep['amount'], 2); ?>
                                                            </span> руб.
                                                        </p>
                                                    </div>
                                                    <div class="fee-history__col">
                                                        <?php if ($dep['way'] == 0): ?>
                                                            <p>Карта</p>
                                                        <?php else: ?>
                                                            <p>Счёт</p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="fee-history__col">
                                                        <p class="s-fz12px">
                                                            <?php if ($dep['way'] == 1): ?>
                                                                <a href="" class="fee-history__link">скачать акт</a>
                                                            <?php endif; ?>
                                                        </p>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li class="fee-history__item">
                                                <div class="empty_res">
                                                    Пополнений еще не было
                                                </div>
                                            </li>
                                        <?php endif; ?>
                                    </ul>

                                    <a href="<?=Url::to(['/user/payment-deposit', 'd' => $d++]); ?>" class="show-more">
                                        Показать ещё
                                    </a>
                                <?php Pjax::end(); ?>
                            </div>
                        </div>

                        <div class="payment__col">
                            <p class="sub-title">
                                детализация
                            </p>
                            <div class="fee-history">
                                <ul class="fee-history__list">
                                    <?php if(!empty($payments)): ?>
                                        <?php foreach($payments as $payment): ?>
                                            <li class="fee-history__item">
                                                <div class="fee-history__col">
                                                    <p><?=Yii::$app->formatter->asDate($payment['date']); ?></p>
                                                </div>
                                                <div class="fee-history__col">
                                                    <p>
                                                    <span class="s-medium">
                                                        <?=Yii::$app->formatter->asDecimal($payment['amount'], 2); ?>
                                                    </span> руб.
                                                    </p>
                                                </div>
                                                <div class="fee-history__col">
                                                    <?php $name = '';
                                                    if (!empty($payment['tariff'])) {
                                                        $name = $payment['tariff']['name'];
                                                    } else {
                                                        $name = $payment['addition']['name'];
                                                    }
                                                    ?>
                                                    <p class="s-fz12px"><?= $payment['description']; ?>: <?= $name; ?></p>
                                                </div>
                                                <div class="fee-history__col">
                                                    <?php if($payment['shop']): ?>
                                                        <p class="s-fz12px"><?= $payment['shop']['address'];?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="fee-history__item">
                                            <div class="empty_res">
                                                Списаний еще не было
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

$script = <<< JS
    $('input[name="paid"]').on('change', function() {
        $('#f3 input[name="NewPaid[newPaid]"]').val(this.value);
        $('#f4 input[name="NewPaid[newPaid]"]').val(this.value);
    });
    
    $('#f3 .bill-btn__link').val('click', function(e) {
        if ($('input[name="paid"]').val() == '') {
            e.preventDefault();
            $('.help-block').addClass('has-error');
            $('.help-block').html('Поле не может быть пустым');
        }
    });
    
    $('#f4 .bill-btn__link').on('click', function(e) {
        if ($('input[name="paid"]').val() == '') {
            e.preventDefault();
            $('.help-block').addClass('has-error');
            $('.help-block').html('Поле не может быть пустым');
        }
    });

JS;
//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, View::POS_READY);

?>
