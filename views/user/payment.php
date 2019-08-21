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
/** @var TYPE_NAME $maxPaymentId */

//--
$amount = 0;
$client_email = Yii::$app->user->identity->email;
if (iconv_strlen((string)Yii::$app->user->id) == 1) {
    $client_id = 'aa' . (string)Yii::$app->user->id;
} elseif (iconv_strlen((string)Yii::$app->user->id) == 2) {
    $client_id = 'a' . (string)Yii::$app->user->id;
}
$description = 'Пополнение баланса с карты';
$fail_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/user/false-payment';
$merchant = Yii::$app->params['idSite'];
$order_id = $maxPaymentId['id'] + 1;
$salt = Yii::$app->security->generateRandomString(32);
$success_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/user/success-payment';
$testing = 1;
$unix_timestamp = time();

$secretKey = Yii::$app->params['testSecretKey'];
//--

$this->title = 'Детализация баланса'; ?>

<div class="content">
    <h2 class="content__title">Баланс</h2>
    <div class="content__row">
        <div class="content__col-12">
            <?php if(Yii::$app->session->hasFlash('success')): ?>
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Успех</h4>
                    <hr>
                    <p class="mb-0"><?php echo Yii::$app->session->getFlash('success'); ?></p>
                </div>
            <?php endif; ?>
            <?php if(Yii::$app->session->hasFlash('error')): ?>
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Ошибка</h4>
                    <hr>
                    <p class="mb-0"><?php echo Yii::$app->session->getFlash('error'); ?></p>
                </div>
            <?php endif; ?>

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
                                    <?php $form1 = ActiveForm::begin(['action' => Url::to(['/user/save-pdf']), 'id' => 'f3']); ?>
                                        <?= $form1->field($modelPaid, 'amount')->hiddenInput(['value' => 0])
                                            ->label(false)->error(false); ?>
                                        <?= Html::submitButton('Выставить счёт', ['class' => 'bill-btn__link']); ?>
                                    <?php $form1 = ActiveForm::end(); ?>
                                </div>

                                <div class="bill-btn__box">
                                    <div class="bill-btn__icon">
                                        <?=Html::img('@web/images/icon/icon-bill-cards.svg'); ?>
                                    </div>
                                    <?php $form2 = ActiveForm::begin(['action' => Url::to('https://pay.modulbank.ru/pay'),
                                        'id' => 'f4']); ?>
                                        <input type="hidden" name="amount" value="<?= $amount; ?>">
                                        <input type="hidden" name="merchant" value="<?= $merchant; ?>">
                                        <input type="hidden" name="order_id" value="<?= $order_id; ?>">
                                        <input type="hidden" name="description" value="<?= $description; ?>">
                                        <input type="hidden" name="success_url" value="<?= $success_url; ?>">
                                        <input type="hidden" name="fail_url" value="<?= $fail_url; ?>">
                                        <input type="hidden" name="client_email" value="<?= $client_email; ?>">
                                        <input type="hidden" name="client_id" value="<?= $client_id; ?>">
                                        <input type="hidden" name="unix_timestamp" value="<?= $unix_timestamp; ?>">
                                        <input type="hidden" name="salt" value="<?= $salt; ?>">
                                        <input type="hidden" name="testing" value="<?= $testing; ?>">
                                        <input type="hidden" name="signature" value="">
                                        <?= Html::submitButton('Оплатить картой', ['class' => 'bill-btn__link']); ?>
                                    <?php $form2 = ActiveForm::end(); ?>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="payment__tab-mobile js_tab-parent">
                        <ul class="payment__tab">
                            <li class="payment__tab-item js__tab-item active">
                                <p class="sub-title sub-title-mobile">
                                    Счета
                                </p>
                            </li>
                            <li class="payment__tab-item js__tab-item">
                                <p class="sub-title sub-title-mobile">
                                    Оплаты
                                </p>
                            </li>
                            <li class="payment__tab-item js__tab-item">
                                <p class="sub-title sub-title-mobile">
                                    Детализация
                                </p>
                            </li>
                        </ul>

                        <ul class="payment__content">
                            <?php Pjax::begin(); ?>
                                <?php $new_d = $d++; $new_i = $i++; ?>
                                <li class="payment__content-item js__content-item">
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
                                                        <?php elseif ($inv['status'] == Payments::STATUS_EXPOSED): ?>
                                                            <p class="table__status table__status_off">Выставлен</p>
                                                        <?php else: ?>
                                                            <p class="table__status table__status_off">Отменён</p>
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

                                    <a href="<?=Url::to(['/user/payment', 'd' => $new_d, 'i' => $i]); ?>" class="show-more">
                                        Показать ещё
                                    </a>
                                </li>

                                <li class="payment__content-item js__content-item">
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

                                    <a href="<?=Url::to(['/user/payment', 'd' => $d, 'i' => $new_i]); ?>" class="show-more">
                                        Показать ещё
                                    </a>
                                </li>
                            <?php Pjax::end(); ?>

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
                            <?php Pjax::begin(); ?>
                                <div class="check-status">
                                    <p class="sub-title">
                                        Созданные счета
                                    </p>
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
                                                    <?php elseif ($inv['status'] == Payments::STATUS_EXPOSED): ?>
                                                        <p class="check-status__condition check-status__condition_off">Выставлен</p>
                                                    <?php else: ?>
                                                        <p class="check-status__condition check-status__condition_off">Отменён</p>
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

                                    <a href="<?=Url::to(['/user/payment', 'd' => $new_d, 'i' => $i]); ?>" class="show-more">
                                        Показать ещё
                                    </a>
                                </div>

                                <div class="fee-history fee-history_pt60">
                                    <p class="sub-title">
                                        История оплат
                                    </p>
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
                                                                <a href="<?= Url::to(['/user/download-act', 'id' => $dep['id']]);?>" class="fee-history__link" data-pjax="0">
                                                                    Скачать акт
                                                                </a>
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

                                    <a href="<?=Url::to(['/user/payment', 'd' => $d, 'i' => $new_i]); ?>" class="show-more">
                                        Показать ещё
                                    </a>
                                </div>
                            <?php Pjax::end(); ?>
                        </div>

                        <div class="payment__col">
                            <p class="sub-title">
                                Детализация
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
                                                    if ($payment['type_service'] == 1) {
                                                        $name = $payment['tariff']['name'];
                                                    } elseif ($payment['type_service'] == 2) {
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

$amountEncode = base64_encode($amount);
$client_emailEncode = base64_encode($client_email);
$client_idEncode = base64_encode($client_id);
$descriptionEncode = base64_encode($description);
$fail_urlEncode = base64_encode($fail_url);
$merchantEncode = base64_encode($merchant);
$order_idEncode = base64_encode($order_id);
$saltEncode = base64_encode($salt);
$success_urlEncode = base64_encode($success_url);
$testingEncode = base64_encode($testing);
$unix_timestampEncode = base64_encode($unix_timestamp);

$script = <<< JS
    const stringOne = 'amount=$amountEncode';

    let stringTwo = '&client_email=$client_emailEncode&client_id=$client_idEncode&description=$descriptionEncode';
    stringTwo += '&fail_url=$fail_urlEncode&merchant=$merchantEncode&order_id=$order_idEncode&salt=$saltEncode';
    stringTwo += '&success_url=$success_urlEncode&testing=$testingEncode&unix_timestamp=$unix_timestampEncode';
    
    let signatureOne = sha1('$secretKey' + stringOne + stringTwo);
    let signatureTwo = sha1('$secretKey' + signatureOne);
    $('#f4 input[name="signature"]').val(signatureTwo);
    
    $('input[name="paid"]').on('change', function() {
        $('#f3 input[name="NewPaid[amount]"]').val(this.value);
        $('#f4 input[name="amount"]').val(this.value);
    });
    
    $('#f3 .bill-btn__link').val('click', function(e) {
        if ($('input[name="paid"]').val() == '') {
            e.preventDefault();
            $('.help-block').addClass('has-error');
            $('.help-block').html('Поле не может быть пустым');
        }
    });
    
    $('#f4 .bill-btn__link').on('click', function(e) {
        e.preventDefault();
        if ($('input[name="paid"]').val() != '') {
            let string = 'amount='+ btoa($('#f4 input[name="amount"]').val());
            string += stringTwo;
            
            let signatureThree = sha1('$secretKey' + string);
            let signatureFour = sha1('$secretKey' + signatureThree);

            $('#f4 input[name="signature"]').val(signatureFour);
            $('#f4').submit();
        } else {
            $('.help-block').addClass('has-error');
            $('.help-block').html('Поле не может быть пустым');
        }
    });
JS;

//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, View::POS_READY);

?>
