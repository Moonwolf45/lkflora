<?php

use app\models\payments\Payments;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var TYPE_NAME $shops */
/** @var TYPE_NAME $invoice */
/** @var TYPE_NAME $tickets */
/** @var TYPE_NAME $monthly_payment */

$this->title = 'Главная'; ?>

<div class="content content-main content-advertising">
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
                                    <h3 class="shops__box-title">
                                        Ваши магазины
                                    </h3>
                                    <div class="add-something" data-jsx-modal-target="store">
                                        <div class="add-something__plus s-di-vertical-m"></div>
                                        <p class="add-something__text add-something__text_fs14 s-di-vertical-m">добавить магазин</p>
                                    </div>
                                </div>
                            </div>
                            <?php foreach ($shops as $shop): ?>
                                <?php $editStore_id = md5($shop['id']); $addressShop = $shop['address']; ?>
                                <ul class="shops__list">
                                    <?php $id_modal = md5($shop['id'] . '_' . $shop['tariff']['id']);
                                        $tariff_id = $shop['tariff']['id']; $shop_id = $shop['id']; ?>
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
                                    <?php $shopsAdditions = [];
                                        $count_addition = 0;
                                    if (!empty($shop['additions'])):
                                        $count_addition = count($shop['additions']);
                                        foreach ($shop['shopsAdditions'] as $key => $shopAddition) {
                                            $shopsAdditions[$shopAddition['shop_id'] . '_' . $shopAddition['addition_id']] = $shop['shopsAdditions'][$key];
                                        }

                                        foreach ($shop['additions'] as $key => $addition): ?>
                                            <li class="shops__item shops__item_pb12">
                                                <div class="shops__item-box shops__item-box_df">
                                                    <div class="shops__item-icon">
                                                        <?=Html::img('@web/images/icon/flower.svg'); ?>
                                                    </div>
                                                    <p class="shops__item-box-text"><?= $addition['name']; ?>: </p>

                                                    <div class="shops__item-tariff">
                                                        <p class="shops__item-tariff-text">
                                                            <?=Yii::$app->formatter->asDecimal($addition['cost'], 2); ?>
                                                            <?php if ($addition['type'] == 1): ?>
                                                                руб
                                                            <?php else: ?>
                                                                руб/мес
                                                            <?php endif; ?>
                                                        </p>
                                                        <p class="shops__item-box-text">
                                                            Количество: <?= $shop['shopsAdditions'][$key]['quantity']; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <li class="shops__item">
                                        <?php if($count_addition == 0): ?>
                                            <div class="add-something" data-jsx-modal-target="addService_<?=$id_modal; ?>">
                                                <div class="add-something__plus s-di-vertical-m"></div>
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
                                    'id_modal', 'tariff_id', 'shop_id')); ?>
                                <?php echo $this->render('modal/add_service', compact('modelShop', 'id_modal',
                                    'shop_id', 'additions', 'shopsAdditions')); ?>
                            <?php endforeach; ?>
                        <?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="content__col-3 content__col_add-services">
            <div class="content__box">
                <div class="services">
                    <div class="services__wrapp">
                        <p class="sub-title">
                            Подключено услуг на
                        </p>
                        <?php $total_payment = 0;
                        if (!empty($monthly_payment)) {
                            foreach ($monthly_payment as $m_payment) {
                                $total_payment += $m_payment['writeoff_amount'];
                            }
                        } ?>
                        <p class="services__total"><?= Yii::$app->formatter->asDecimal($total_payment, 2); ?> руб/мес</p>
                        <a href="<?= Url::to(['/user/payment', 'd' => 1, 'i' => 1]); ?>" class="services__detalization">детализация</a>
                        <p class="sub-title">
                            Созданные счета
                        </p>
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
                            <p class="sub-title sub-title_pl20">
                                Техподдержка
                            </p>
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

<?php echo $this->render('modal/store', compact('modelShop', 'tariffs', 'additions')); ?>
<?php echo $this->render('modal/appeal', compact('newTicket')); ?>
