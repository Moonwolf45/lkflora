<?php

use yii\helpers\Html;

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
                                <?=Yii::$app->formatter->asDecimal('4500', 2); ?>
                                <span class="payment__balance-span">руб</span>
                            </p>
                        </div>
                        <div class="payment__col payment__col_mobile">
                            <div class="little-title">Пополнить баланс</div>
                            <div class="field payment__field">
                                <input type="text" class="input" placeholder="Введите сумму для пополнения">
                            </div>
                            <div class="bill-btn">
                                <div class="bill-btn__box">
                                    <div class="bill-btn__icon">
                                        <?=Html::img('@web/images/icon/icon-bill-pdf.svg'); ?>
                                    </div>
                                    <a href="#" class="bill-btn__link">Выставить счёт</a>
                                </div>
                                <div class="bill-btn__box">
                                    <div class="bill-btn__icon">
                                        <?=Html::img('@web/images/icon/icon-bill-cards.svg'); ?>
                                    </div>
                                    <a href="#" class="bill-btn__link">Оплатить картой</a>
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
                                <table class="table">
                                    <tr class="table__row">
                                        <td class="table__col">
                                            <p class="table__text">Счет № <span class="s-medium">11283</span> от 21.02.2019</p>
                                        </td>
                                        <td class="table__col">
                                            <p><span class="s-medium">900</span> руб.</p>
                                        </td>
                                        <td class="table__col">
                                            <p class="table__status table__status_off">Не оплачен</p>
                                        </td>
                                    </tr>
                                    <tr class="table__row table__row-line">
                                        <td class="table__col">
                                            <p class="table__text">Счет № <span class="s-medium">11283</span> от 21.02.2019</p>
                                        </td>
                                        <td class="table__col">
                                            <p><span class="s-medium">900</span> руб.</p>
                                        </td>
                                        <td class="table__col">
                                            <p class="table__status table__status_on">оплачен</p>
                                        </td>
                                    </tr>
                                    <tr class="table__row table__row-line">
                                        <td class="table__col">
                                            <p class="table__text">Счет № <span class="s-medium">11283</span> от 21.02.2019</p>
                                        </td>
                                        <td class="table__col">
                                            <p><span class="s-medium">900</span> руб.</p>
                                        </td>
                                        <td class="table__col">
                                            <p class="table__status table__status_off">Не оплачен</p>
                                        </td>
                                    </tr>
                                </table>

                                <a href="#" class="show-more">Показать ещё</a>
                            </li>
                            <li class="payment__content-item js__content-item">
                                <ul class="fee-history__list fee-history__list_history">
                                    <li class="fee-history__item">
                                        <div class="fee-history__col">
                                            <p>21.02.2019</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p><span class="s-medium">900</span> руб.</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p>Счёт</p>
                                        </div>
                                        <div class="fee-history__col s-t-a-right">
                                            <p class="s-fz12px">
                                                <a href="" class="fee-history__link">скачать акт</a>
                                            </p>
                                        </div>
                                    </li>
                                    <li class="fee-history__item">
                                        <div class="fee-history__col">
                                            <p>21.02.2019</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p><span class="s-medium">900</span> руб.</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p>Карта</p>
                                        </div>
                                        <div class="fee-history__col s-t-a-right">
                                            <p class="s-fz12px">
                                            </p>
                                        </div>
                                    </li>
                                    <li class="fee-history__item">
                                        <div class="fee-history__col">
                                            <p>21.02.2019</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p><span class="s-medium">900</span> руб.</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p>Счёт</p>
                                        </div>
                                        <div class="fee-history__col s-t-a-right">
                                            <p class="s-fz12px">
                                                <a href="" class="fee-history__link">скачать акт</a>
                                            </p>
                                        </div>
                                    </li>
                                </ul>
                                <a href="#" class="show-more">Показать ещё</a>
                            </li>
                            <li class="payment__content-item js__content-item">
                                <ul class="fee-history__list">
                                    <li class="fee-history__item">
                                        <div class="fee-history__col">
                                            <p>21.02.2019</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p><span class="s-medium">900</span> руб.</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p class="s-fz12px">Оплата техподдержки</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p class="s-fz12px">Москва, ул. Ленина д 1 оф 20</p>
                                        </div>
                                    </li>
                                    <li class="fee-history__item">
                                        <div class="fee-history__col">
                                            <p>21.02.2019</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p><span class="s-medium">900</span> руб.</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p class="s-fz12px">Оплата техподдержки</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p class="s-fz12px">Москва, ул. Ленина д 1 оф 20</p>
                                        </div>
                                    </li>
                                    <li class="fee-history__item">
                                        <div class="fee-history__col">
                                            <p>21.02.2019</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p><span class="s-medium">900</span> руб.</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p class="s-fz12px">Оплата техподдержки</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p class="s-fz12px">Москва, ул. Ленина д 1 оф 20</p>
                                        </div>
                                    </li>
                                    <li class="fee-history__item">
                                        <div class="fee-history__col">
                                            <p>21.02.2019</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p><span class="s-medium">900</span> руб.</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p class="s-fz12px">Оплата техподдержки</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p class="s-fz12px">Москва, ул. Ленина д 1 оф 20</p>
                                        </div>
                                    </li>
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
                                <div class="check-status__wrapp">
                                    <div class="check-status__block">
                                        <p class="check-status__text">Счет № <span class="check-status__span">11283</span>  от 21.02.2019</p>
                                        <p class="check-status__text"><span class="check-status__span">900</span> руб.</p>
                                        <p class="check-status__condition check-status__condition_off">Не оплачен</p>
                                    </div>
                                    <div class="check-status__block">
                                        <p class="check-status__text">Счет № <span class="check-status__span">11283</span>  от 21.02.2019</p>
                                        <p class="check-status__text"><span class="check-status__span">900</span> руб.</p>
                                        <p class="check-status__condition check-status__condition_off">Не оплачен</p>
                                    </div>
                                    <div class="check-status__block">
                                        <p class="check-status__text">Счет № <span class="check-status__span">11283</span>  от 21.02.2019</p>
                                        <p class="check-status__text"><span class="check-status__span">900</span> руб.</p>
                                        <p class="check-status__condition check-status__condition_on">Оплачен</p>
                                    </div>
                                </div>
                                <a href="#" class="show-more">Показать ещё</a>
                            </div>
                            <div class="fee-history fee-history_pt60">
                                <p class="sub-title">
                                    История оплат
                                </p>
                                <ul class="fee-history__list fee-history__list_history">
                                    <li class="fee-history__item">
                                        <div class="fee-history__col">
                                            <p>21.02.2019</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p><span class="s-medium">900</span> руб.</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p>Счёт</p>
                                        </div>
                                        <div class="fee-history__col s-t-a-right">
                                            <p class="s-fz12px">
                                                <a href="" class="fee-history__link">скачать акт</a>
                                            </p>
                                        </div>
                                    </li>
                                    <li class="fee-history__item">
                                        <div class="fee-history__col">
                                            <p>21.02.2019</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p><span class="s-medium">900</span> руб.</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p>Карта</p>
                                        </div>
                                        <div class="fee-history__col s-t-a-right">
                                            <p class="s-fz12px">
                                            </p>
                                        </div>
                                    </li>
                                    <li class="fee-history__item">
                                        <div class="fee-history__col">
                                            <p>21.02.2019</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p><span class="s-medium">900</span> руб.</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p>Счёт</p>
                                        </div>
                                        <div class="fee-history__col s-t-a-right">
                                            <p class="s-fz12px">
                                                <a href="" class="fee-history__link">скачать акт</a>
                                            </p>
                                        </div>
                                    </li>
                                </ul>
                                <a href="#" class="show-more">Показать ещё</a>
                            </div>
                        </div>
                        <div class="payment__col">
                            <p class="sub-title">
                                детализация
                            </p>
                            <div class="fee-history">
                                <ul class="fee-history__list">
                                    <li class="fee-history__item">
                                        <div class="fee-history__col">
                                            <p>21.02.2019</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p><span class="s-medium">900</span> руб.</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p class="s-fz12px">Оплата техподдержки</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p class="s-fz12px">Москва, ул. Ленина д 1 оф 20</p>
                                        </div>
                                    </li>
                                    <li class="fee-history__item">
                                        <div class="fee-history__col">
                                            <p>21.02.2019</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p><span class="s-medium">900</span> руб.</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p class="s-fz12px">Оплата техподдержки</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p class="s-fz12px">Москва, ул. Ленина д 1 оф 20</p>
                                        </div>
                                    </li>
                                    <li class="fee-history__item">
                                        <div class="fee-history__col">
                                            <p>21.02.2019</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p><span class="s-medium">900</span> руб.</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p class="s-fz12px">Оплата техподдержки</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p class="s-fz12px">Москва, ул. Ленина д 1 оф 20</p>
                                        </div>
                                    </li>
                                    <li class="fee-history__item">
                                        <div class="fee-history__col">
                                            <p>21.02.2019</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p><span class="s-medium">900</span> руб.</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p class="s-fz12px">Оплата техподдержки</p>
                                        </div>
                                        <div class="fee-history__col">
                                            <p class="s-fz12px">Москва, ул. Ленина д 1 оф 20</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
