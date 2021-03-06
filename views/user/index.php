<?php

use app\models\MessageToPaid;
use app\models\payments\Payments;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var $shops app\models\shops\Shops */
/** @var $modelShop app\models\shops\Shops */
/** @var $tariffs app\models\tariff\Tariff */
/** @var $additions app\models\addition\Addition */
/** @var $invoice app\models\payments\Payments */
/** @var $tickets app\models\tickets\Tickets */
/** @var $newTicket app\models\tickets\Tickets */
/** @var $monthly_payment app\models\service\Service */
/** @var $next_payment app\models\MessageToPaid */

$this->title = 'Главная'; ?>

<div class="content content-main content-advertising">
    <?php if (!empty($next_payment)): ?>
        <?php $debtor = 0; $tomorrow = 0; $after_tomorrow = 0; $after_the_day_after_tomorrow = 0;

            foreach ($next_payment as $nP) {
                if ($nP['date_to_paid'] == date("Y-m-d") || $nP['debtor'] == MessageToPaid::DEBTOR_YES) {
                    $debtor += $nP['amount'];
                }

                if ($nP['date_to_paid'] == date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")))) {
                    $tomorrow += $nP['amount'];
                }

                if ($nP['date_to_paid'] == date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 2, date("Y")))) {
                    $after_tomorrow += $nP['amount'];
                }

                if ($nP['date_to_paid'] == date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 3, date("Y")))) {
                    $after_the_day_after_tomorrow += $nP['amount'];
                }
            }
        ?>

        <?php if ($tomorrow != 0 || $after_tomorrow != 0 || $after_the_day_after_tomorrow != 0): ?>
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">Важно!</h4>
                <hr>

                <?php if ($tomorrow != 0): ?>
                    <p class="mb-0">Завтра у вас будет списана оплата за тариф\доп. услуги в размере
                        <b><?= Yii::$app->formatter->asDecimal($tomorrow, 2); ?> руб.</b>
                    </p>
                <?php endif; ?>

                <?php if ($after_tomorrow != 0): ?>
                    <p class="mb-0">Послезавтра у вас будет списана оплата за тариф\доп. услуги в размере
                        <b><?= Yii::$app->formatter->asDecimal($after_tomorrow, 2); ?> руб.</b>
                    </p>
                <?php endif; ?>

                <?php if ($after_the_day_after_tomorrow != 0): ?>
                    <p class="mb-0">Через 3 дня у вас будет списана оплата за тариф\доп. услуги в размере
                        <b><?= Yii::$app->formatter->asDecimal($after_the_day_after_tomorrow, 2); ?> руб.</b>
                    </p>
                <?php endif; ?>
                <br>

                <p class="mb-0">Пожулайста проследите, что бы у вас на балансе хватило денег на оплату услуг.</p>
            </div>
        <?php endif; ?>

        <?php if ($debtor != 0): ?>
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">Внимание!</h4>
                <hr>
                <p class="mb-0">У вас имеется долг за тариф\доп. услуги в размере
                    <b><?= Yii::$app->formatter->asDecimal($debtor, 2); ?> руб.</b><br>
                    <br>
                    Пожалуйста пополните баланс на данную сумму.
                </p>
            </div>
        <?php endif; ?>

    <?php endif; ?>

    <div class="content__row content__row_main">
        <div class="content__col-6 content__col_shops">
            <div class="content__box content__box_pb85">
                <div class="shops">
                    <div class="shops__wrapp">
                        <?php Pjax::begin(); ?>
                            <div class="shops__block">
                                <div class="shops__box s-di-vertical-m shops__box_pr40">
                                    <div class="shops__image">
                                        <?=Html::img('@web/images/shops-image.png', ['class' => 'shops__img']); ?>
                                        <div class="shops-count">
                                            <p class="shops-count__number"><?= count($shops); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="shops__box s-di-vertical-m">
                                    <h3 class="shops__box-title">Ваши магазины</h3>
                                    <div class="add-something" data-jsx-modal-target="store">
                                        <div class="plus s-di-vertical-m"></div>
                                        <p class="add-something__text s-fz14px s-di-vertical-m">добавить магазин</p>
                                    </div>
                                </div>
                            </div>
                            <?php if (!empty($shops)): ?>
                                <?php foreach ($shops as $shop): ?>
                                    <?php $editStore_id = md5($shop['id']); $addressShop = $shop['address'];
                                        $id_modal = md5($shop['id'] . '_' . $shop['tariff']['id']);
                                        $tariff_id = $shop['tariff']['id']; $shop_id = $shop['id'];
                                        $count_addition = 0; $shopsAdditions = $shop['shopsAdditions'];
                                        $class_to_shop = '';
                                    ?>

                                    <?php if($shop['on_check']): ?>
                                        <?php $class_to_shop = 'shops__list_processing';?>
                                    <?php endif; ?>
                                    <ul class="shops__list <?= $class_to_shop; ?>">
                                        <li class="shops__item-mobile">
                                            <div class="shops__item-box s-di-vertical-m shops__item-title">Тариф</div>
                                            <div class="shops__item-box shops__item-box-mobile s-di-vertical-m" data-jsx-modal-target="tariff_<?=$id_modal; ?>">
                                                <a class="shops__item-box-link shops__item-name">
                                                    <?=$shop['tariff']['name']; ?>
                                                </a>
                                            </div>
                                        </li>
                                        <li class="shops__item">
                                            <div class="shops__item-box shops__item-title">Адрес</div>
                                            <?php if($shop['on_check']): ?>
                                                <div class="shops__item-box shops__item-request">
                                                    <p>Запрос обрабатывается</p>
                                                    <div class="shops__request-help">
                                                        <p class="shops__request-help-icon hint-- hint--top hint--info" data-hint="Ваш запрос отправлен и обрабатывается  администрацией FloraPoint. После подключения услуги, она станет активна."></p>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <div class="shops__item-box shops__item-box_mw115 shops__item-title">Тариф</div>
                                        </li>
                                        <li class="shops__item shops__item_p2">
                                            <div class="shops__item-box shops__item-box-link shops__item-name" data-jsx-modal-target="editStore_<?=$editStore_id; ?>">
                                                <?=$addressShop; ?>
                                            </div>
                                            <div class="shops__item-box  shops__item-box_mw115" data-jsx-modal-target="tariff_<?=$id_modal; ?>">
                                                <a class="shops__item-box-link shops__item-name">
                                                    <?=$shop['tariff']['name']; ?>

                                                    <?php if (!$shop['tariff']['maximum']): ?>
                                                        <span class="shops__item-tariff-icon">
                                                            <?= Html::img('@web/images/icon/icon-list-arrow.svg');?>
                                                        </span>
                                                    <?php endif; ?>
                                                </a>
                                            </div>
                                        </li>
                                        <?php if (!empty($shop['shopsAdditions'])):
                                            $count_addition = count($shop['shopsAdditions']);
                                            foreach ($shop['shopsAdditions'] as $sA): ?>
                                                <li class="shops__item shops__item_pb12">
                                                    <div class="shops__item-box shops__item-box_df">
                                                        <div class="shops__item-icon">
                                                            <?=Html::img('@web/images/icon/flower.svg'); ?>
                                                        </div>
                                                        <p class="shops__item-box-text"><?= $sA['addition']['name']; ?>: </p>

                                                        <div class="shops__item-tariff">
                                                            <p class="shops__item-tariff-text">
                                                                <?=Yii::$app->formatter->asDecimal($sA['addition']['cost'], 2); ?>
                                                                <?php if ($sA['addition']['type'] == 1): ?>
                                                                    руб
                                                                <?php else: ?>
                                                                    руб/мес
                                                                <?php endif; ?>
                                                            </p>
                                                            <p class="shops__item-box-text">
                                                                Количество: <?= $sA['quantity']; ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <li class="shops__item">
                                            <?php if ($count_addition == 0): ?>
                                                <div class="add-something" data-jsx-modal-target="addService_<?=$id_modal; ?>">
                                                    <div class="plus s-di-vertical-m"></div>
                                                    <p class="add-something__text s-di-vertical-m">добавить услугу</p>
                                                </div>
                                            <?php else: ?>
                                                <div class="add-something" data-jsx-modal-target="addService_<?=$id_modal; ?>">
                                                    <div class="s-di-vertical-m">
                                                        <?= Html::img('@web/images/icon/icon-edit-service.svg'); ?>
                                                    </div>
                                                    <p class="add-something__text s-di-vertical-m">редактировать услугу</p>
                                                </div>
                                            <?php endif; ?>
                                        </li>
                                    </ul>
                                    <?php echo $this->render('modal/editStore', compact('editStore_id',
                                        'modelShop', 'addressShop', 'shop_id')); ?>
                                    <?php echo $this->render('modal/tariff', compact('modelShop', 'tariffs',
                                        'id_modal', 'tariff_id', 'shop_id', 'shopsAdditions')); ?>
                                    <?php echo $this->render('modal/add_service', compact('modelShop', 'id_modal',
                                        'shop_id', 'tariffs', 'tariff_id', 'shopsAdditions')); ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <ul class="shops__list">
                                    <li class="shops__item">
                                        <div class="shops__item-box shops__item-title"></div>
                                        <div class="shops__item-box shops__item-request">
                                            <p>У вас нет созданных магазинов</p>
                                        </div>
                                        <div class="shops__item-box shops__item-box_mw115 shops__item-title"></div>
                                    </li>
                                </ul>
                            <?php endif; ?>
                        <?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="content__col-3 content__col_add-services">
            <div class="content__box">
                <div class="services">
                    <div class="services__wrapp">
                        <p class="sub-title">Подключено услуг на </p>
                        <?php $total_payment = 0;
                        if (!empty($monthly_payment)) {
                            foreach ($monthly_payment as $m_payment) {
                                $total_payment += $m_payment['writeoff_amount'];
                            }
                        } ?>
                        <p class="services__total"><?= Yii::$app->formatter->asDecimal($total_payment, 2); ?> руб/мес</p>
                        <a href="<?= Url::to(['/user/payment', 'd' => 1, 'i' => 1]); ?>" class="services__detalization">детализация</a>
                        <p class="sub-title">Созданные счета</p>
                        <div class="check-status">
                            <?php if(!empty($invoice)): ?>
                                <?php foreach($invoice as $inv): ?>
                                    <div class="check-status__block">
                                        <a href="<?= Url::to(['/user/download-pdf', 'id' => $inv['id'],
                                            'invoice_number' => $inv['invoice_number']]); ?>" class="check-status__text" target="_blank" data-pjax="0">
                                            Счет № <span class="check-status__span"><?= $inv['invoice_number']; ?></span>
                                        </a>
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
                    </div>
                </div>
            </div>
        </div>

        <div class="content__col-3 content__col_support">
            <div class="content__box">
                <div class="support">
                    <div class="support__wrapp">
                        <div class="support__block_top">
                            <p class="sub-title sub-title_pl20 sub-title_desktop">Техподдержка</p>
                            <button class="button button_width-200px support__button" data-jsx-modal-target="appeal">
                                Создать обращение
                            </button>
                        </div>
                        <?php Pjax::begin(); ?>
                            <div class="support__block">
                                <?php if (!empty($tickets)): ?>
                                    <?php foreach ($tickets as $ticket): ?>
                                        <a class="support__box" data-pjax="0" href="<?= Url::to(['/user/tickets', 'id' => $ticket['id']]); ?>">
                                            <?php if ($ticket['new_text']) {
                                                $dop_class = 'support__box-title_circle';
                                            } ?>
                                            <p class="support__box-title <?php echo $dop_class; ?>">
                                                <?= $ticket['subject']; ?>
                                            </p>
                                            <p class="support__box-text">
                                                <?= $ticket['lastTicket']['text']; ?>
                                            </p>
                                        </a>
                                    <?php endforeach; ?>

                                    <?= Html::a('Все обращения', ['/user/tickets'], ['class' => 'support__box-text all-tickets',
                                        'data-pjax' => 0]); ?>
                                <?php else: ?>
                                    <div class="support__box">
                                        <p class="support__box-text">
                                            Открытых обращений нет
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->render('modal/store', compact('modelShop', 'tariffs')); ?>
<?php echo $this->render('modal/appeal', compact('newTicket')); ?>
