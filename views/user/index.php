<?php

use app\models\shops\Shops;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'Главная'; ?>

<div class="content  content-main content-advertising">
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
                                            <p class="shops-count__number"><?=count($shops); ?></p>
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
                                <ul class="shops__list">
                                    <?php $id_modal_version = md5($shop['id'] . '_' . $shop['version']);
                                        $version = $shop['version']; ?>
                                    <li class="shops__item-mobile">
                                        <div class="shops__item-box s-di-vertical-m shops__item-title">версия</div>
                                        <div class="shops__item-box shops__item-box-mobile s-di-vertical-m" data-jsx-modal-target="version-change_<?=$id_modal_version; ?>">
                                            <a href="#" class="shops__item-box-link shops__item-name">
                                                <?=Shops::getVersion($version); ?>
                                            </a>
                                        </div>
                                    </li>
                                    <li class="shops__item">
                                        <div class="shops__item-box shops__item-title">Адрес</div>
                                        <div class="shops__item-box shops__item-box_mw115 shops__item-title">версия</div>
                                    </li>
                                    <li class="shops__item shops__item_p2">
                                        <div class="shops__item-box shops__item-name"><?=$shop['address']; ?></div>
                                        <div class="shops__item-box  shops__item-box_mw115" data-jsx-modal-target="version-change_<?=$id_modal_version; ?>">
                                            <a href="#" class="shops__item-box-link shops__item-name">
                                                <?=Shops::getVersion($version); ?>
                                            </a>
                                        </div>
                                    </li>
                                    <li class="shops__item shops__item_pb12">
                                        <div class="shops__item-box shops__item-box_df">
                                            <div class="shops__item-icon">
                                                <?=Html::img('@web/images/icon/icon-lifebuoy.svg'); ?>
                                            </div>
                                            <p class="shops__item-box-text">Техподдержка:
                                            </p>
                                            <?php $id_modal_tariff = md5($shop['id'] . '_' . $shop['tariff']['id']);
                                                $tariff_id = $shop['tariff']['id']; $shop_id = $shop['id']; ?>
                                            <div class="shops__item-tariff" data-jsx-modal-target="tariff_<?=$id_modal_tariff; ?>">
                                                <a href="#" class="shops__item-tariff-text">
                                                    <?=$shop['tariff']['name']; ?> (<?=Yii::$app->formatter->asDecimal($shop['tariff']['cost'], 2); ?> руб/мес)
                                                </a>
                                                <a href="#" class="shops__item-tariff-icon">
                                                    <?=Html::img('@web/images/icon/icon-list-arrow.svg'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="shops__item">
                                        <div class="add-something">
                                            <div class="add-something__plus s-di-vertical-m"></div>
                                            <p class="add-something__text s-di-vertical-m">добавить услугу</p>
                                        </div>
                                    </li>
                                </ul>
                                <?php echo $this->render('modal/tariff', compact('modelShop', 'tariffs',
                                    'id_modal_tariff', 'tariff_id', 'shop_id')); ?>
                                <?php echo $this->render('modal/version-change', compact('version',
                                    'id_modal_version')); ?>
                            <?php endforeach; ?>
                        <?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="content__col-3 content__col_add-services">
            <div class="content__box">
                <div class="add-services">
                    <div class="add-services__wrapp">
                        <p class="sub-title">
                            Подключено услуг на
                        </p>
                        <p class="add-services__total">2 450 руб/мес</p>
                        <p class="add-services__detalization">детализация</p>
                        <p class="sub-title">
                            Созданные счета
                        </p>
                        <div class="check-status">
                            <div class="check-status__block">
                                <p class="check-status__text">Счет № <span class="check-status__span">11283</span></p>
                                <p class="check-status__condition check-status__condition_off">Не оплачен</p>
                            </div>
                            <div class="check-status__block">
                                <p class="check-status__text">Счет № <span class="check-status__span">11283</span></p>
                                <p class="check-status__condition check-status__condition_off">Не оплачен</p>
                            </div>
                            <div class="check-status__block">
                                <p class="check-status__text">Счет № <span class="check-status__span">11283</span></p>
                                <p class="check-status__condition check-status__condition_on">Оплачен</p>
                            </div>
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
                        <div class="support__block">
                            <div class="support__box">
                                <p class="support__box-title support__box-title_circle">
                                    Как собрать букет ?
                                </p>
                                <p class="support__box-text">
                                    Вы можете пройти в разделе там
                                </p>
                            </div>
                            <div class="support__box">
                                <p class="support__box-title">
                                    Хочу построить от ?
                                </p>
                                <p class="support__box-text">
                                    То что Вы ищете нахо
                                </p>
                            </div>
                            <div class="support__box">
                                <p class="support__box-title">
                                    Как собрать букет на подарок ?
                                </p>
                                <p class="support__box-text">
                                    Вы можете пройти в разделе там
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php echo $this->render('modal/store', compact('modelShop', 'tariffs')); ?>
<?php echo $this->render('modal/appeal'); ?>
