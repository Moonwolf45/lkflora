<?php

use yii\helpers\Html;

/** @var TYPE_NAME $number */
/** @var TYPE_NAME $date */

?>

<style>
    body {
        font-family: Roboto, sans-serif;
        font-weight: 500;
        color: #000;
        background: #fff;
    }

    * {
        margin: 0;
        padding: 0;
        text-decoration: none;
        box-sizing: border-box;
    }

    p {
        margin: 0;
    }

    .container {
        position: relative;
        width: 1190px;
        margin: 0 auto;
        padding: 0;
    }

    .header {
        float: left;
        width: 100%;
    }

    .header__row {
        display: flex;
        width: 100%;
        -webkit-justify-content: space-between;
        justify-content: space-between;
        padding: 20px 0 10px 0;
    }

    .header__col {
        float: left;
        width: 34%;
        padding-right: 1%;
        padding-left: 1%;
    }

    .header__col .text-normal {
        color: #fff;
        font-size: 12px;
        font-weight: bold;
        background: #FF437F;
        margin: 6px 0;
        padding: 3px 5px;
    }

    .header__col .text-normal_green {
        background: #95C71D;
    }

    .header__col_mw {
        float: left;
        width: 32%;
        padding: 0;
    }

    .header__col_tar {
        margin: 0;
        text-align: right;
    }

    .header__image img {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
    }

    .header__img-text {
        font-size: 15px;
        color: #FF4D87;
        text-align: center;
        letter-spacing: 4px;
        font-weight: 300;
    }

    .main {
        float: left;
        width: 100%;
    }

    .sample-text {
        font-weight: 400;
        text-align: center;
        padding-bottom: 5px;
    }

    .payment {
        border-collapse: collapse;
    }

    .payment tr td {
        border: 1px solid #000;
    }

    .payment__row td:first-child {
        width: 65%;
    }

    .payment__row td:nth-child(3) {
        width: 10%;
    }

    .payment__row td:nth-child(4) {
        width: 35%;
    }

    .payment__inside .payment__text {
        width: 50%;
    }

    .payment__text,
    .goods__text {
        font-size: 14px;
        font-weight: 700;
        padding: 5px;
    }

    .ttu {
        text-transform: uppercase;
    }

    .s-di-vam {
        display: inline-block;
        vertical-align: middle;
    }

    .text-small__payment {
        padding: 0 0 5px 5px;
    }

    .payment__abbre {
        width: 100%;
        padding: 5px;
    }

    .text-small {
        font-size: 14px;
        border-top: 1px solid #000;
    }

    .text-small,
    .payment__abbre {
        font-weight: 300;
    }

    .payment__abbre {
        text-transform: uppercase;
    }

    h2 {
        font-size: 20px;
        font-weight: 700;
        text-align: center;
        padding: 40px 0 10px 0;
        margin: 0;
    }

    hr {
        background: #000;
        height: 4px;
        margin-bottom: 25px;
        margin-top: 0;
        border: none;
    }

    .side-who {
        display: block;
        padding-bottom: 25px;
    }

    .side-who__col_pt {
        padding-top: 15px;
    }

    .side-who__span {
        float: left;
        display: inline-block;
        width: 13%;
        padding-right: 15px;
        font-size: 12px;
    }

    .side-who__text {
        float: left;
        display: inline-block;
        width: 83%;
        font-size: 12px;
        font-weight: bold;
    }

    .goods {
        border-collapse: collapse;
        width: 100%;
        text-align: center;
    }

    .goods th,
    .goods td {
        border: 1px solid #000;
        background: #95C71D;
        color: #FFF;
    }

    .goods th:nth-child(1) {
        width: 5%;
    }

    .goods th:nth-child(2) {
        width: 40%;
    }

    .goods th:nth-child(3) {
        width: 8%;
    }

    .goods th:nth-child(4) {
        width: 10%;
    }

    .goods th:nth-child(5) {
        width: 20%;
    }

    .goods th:nth-child(6) {
        width: 17%;
    }

    .goods td {
        background: none;
        color: #000;
        font-weight: 400;
    }

    .goods td:nth-child(2) {
        text-align: left;
        padding-left: 5px;
    }

    .goods__total {
        float: left;
        width: 100%;
        text-align: right;
        padding: 10px 0 25px 0;
    }

    .goods__col {
        float: right;
        width: 100%;
    }

    .goods__text {
        float: left;
        display: inline-block;
        width: 20%;
        vertical-align: middle;
    }

    .goods__text_pr {
        width: 73.2%;
        padding-right: 30px;
    }

    .text-normal {
        font-weight: 400;
    }

    .text-reg,
    .text-reg_one,
    .text-reg_two {
        display: inline-block;
        width: 100%;
        font-weight: bold;
    }

    .text-reg_pb {
        padding-bottom: 10px;
    }

    .text-reg_border {
        padding: 0 10px;
    }

    .text-reg_initials {
        padding: 15px 0 0 10px;
    }

    .footer {
        position: relative;
        float: left;
        width: 100%;
    }

    .footer__row {
        display: block;
        float: left;
        width: 100%;
        -webkit-align-items: baseline;
        align-items: baseline;
        margin: 0 -10px;
    }

    .footer__col {
        float: left;
        text-align: center;
        padding: 0 10px;
    }

    .one {
        width: 16%;
        text-align: left;
    }

    .two {
        width: 22%;
    }

    .three {
        width: 20%;
    }

    .four {
        width: 25%;
    }

    .footer__box {
        float: left;
        width: 100%;
    }

    .footer__sign {
        width: 50px;
        height: 50px;
    }

    .footer__row_under {
        float: left;
    }

    .one_under {
        width: 40%;
        text-align: left;
    }

    .text-reg_one,
    .text-reg_two {
        float: left;
        width: 30%;
    }

    .text-reg_two {
        width: 20%;
    }

    .two_under {
        width: 20%;
    }

    .three_under {
        width: 30%;
    }

    .footer__box_pt {
        padding-top: 50px;
    }

    .footer__seal {
        float: left;
        top: 15px;
        left: 15px;
    }

    .footer__seal img {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
    }
</style>

<div class="container">
    <div class="header">
        <div class="header__row">
            <div class="header__col header__col_mw">
                <p class="text-normal">FLORAPOINT.RU</p>
                <p class="text-normal text-normal_green">АВТОМАТИЗАЦИЯ</p>
                <p class="text-normal">ФЛОРЕСТИЧЕСКОГО БИЗНЕСА</p>
            </div>
            <div class="header__col">
                <div class="header__image">
                    <?=Html::img('@web/images/bill_logo.png'); ?>
                    <p class="header__img-text">FLORISTIC SOFTWARE</p>
                </div>
            </div>
            <div class="header__col header__col_mw header__col_tar">
                <p class="text-normal">тел.: 8-800-555-66-35</p>
                <p class="text-normal text-normal_green">г. Москва Щелковское щ.100к1</p>
                <p class="text-normal">e-mail: sales@florapoint.ru</p>
            </div>
        </div>
    </div>
    <div class="main">
        <p class="sample-text">Образец заполнения платежного поручения</p>
        <table class="payment">
            <tr class="payment__row">
                <td rowspan="2" colspan="2">
                    <p class="payment__text ttu">МОСКОВСКИЙ ФИЛИАЛ АО КБ "МОДУЛЬБАНК", <br> МОСКВА</p>
                    <p class="text-small__payment">Банк получателя</p>
                </td>
                <td>
                    <span class="payment__abbre payment__abbre_db">БИК</span>
                </td>
                <td rowspan="2">
                    <p class="payment__text">044525092</p>
                    <p class="payment__text">30101810645250000092</p>
                </td>
            </tr>
            <tr class="payment__row">
                <td>
                    <p class="payment__abbre">Сч №</p>
                </td>
            </tr>
            <tr class="payment__row">
                <td>
                    <div class="payment__inside">
                        <p class="payment__text s-di-vam"><span class="payment__abbre">ИНН</span> 5027142964</p>
                    </div>
                </td>
                <td>
                    <div class="payment__inside">
                        <p class="payment__text s-di-vam"><span class="payment__abbre">КПП</span> 5027142964</p>
                    </div>
                </td>
                <td rowspan="2">
                    <p class="payment__abbre">Сч №</p>
                </td>
                <td rowspan="2">
                    <p class="payment__text">40702810645250000092</p>
                </td>
            </tr>
            <tr class="payment__row">
                <td colspan="2">
                    <p class="payment__text">Общество с ограниченной ответсвенностью "Софт технолоджи"</p>
                    <p class="text-small__payment">Получатель</p>
                </td>
            </tr>
        </table>
        <h2>Счет на оплату № <?= $number; ?> от <?= $date; ?>г.</h2>
        <hr>
        <div class="side-who">
            <div class="side-who__col">
                <p class="side-who__span">Поставщик:</p>
                <p class="side-who__text">
                    Общество с ограниченной ответсвенностью "Софт технолоджи", 140006, Московская обл., Люберецкий р-н, Люберци г, Котельнический проезд, Дом № 29А +7 (495)215-28-12
                </p>
            </div>
            <div class="side-who__col side-who__col_pt">
                <p class="side-who__span">Поупатель:</p>
                <p class="side-who__text">
                    Общество с ограниченной ответсвенностью "Всегда дари цветы", 355042, г. Ставрополь, ул. 50 лет ВЛКСМ, д 35Е, оф 203, телефон +79283296616, ИНН2635834309, КПП 263501001
                </p>
            </div>
        </div>
        <table class="goods">
            <tr>
                <th>№</th>
                <th>Товары (работы, услуги)</th>
                <th>Кол-во</th>
                <th>Ед. Изм</th>
                <th>Цена</th>
                <th>Сумма</th>
            </tr>
            <tr>
                <td>1</td>
                <td>Аренда сервера 1 квартал</td>
                <td>1</td>
                <td>шт.</td>
                <td>6 000.00</td>
                <td>6 000.00</td>
            </tr>
        </table>
        <div class="goods__total">
            <div class="goods__col">
                <p class="goods__text goods__text_pr">Итого:</p>
                <p class="goods__text">6 000,00</p>
            </div>
            <div class="goods__col">
                <p class="goods__text goods__text_pr">В том числе НДС:</p>
                <p class="goods__text">Без НДС</p>
            </div>
        </div>

        <p class="text-normal">Всего наименований 1, на сумму 6000.00 руб.</p>
        <p class="text-reg text-reg_pb">Шесть тысяч рублей 00 копеек</p>
        <hr>
    </div>
    <div class="footer">
        <div class="footer__row">
            <div class="footer__col one">
                <p class="text-reg">Руководитель</p>
            </div>
            <div class="footer__col two">
                <div class="footer__box">
                    <p class="text-reg">Генеральный директор</p>
                    <p class="text-small text-small_border">должность</p>
                </div>
            </div>
            <div class="footer__col three">
                <div class="footer__box">
                    <?=Html::img('@web/images/sign.png', ['class' => 'footer__sign text-reg_border']); ?>
                    <p class="text-small text-small_border">подпись</p>
                </div>
            </div>
            <div class="footer__col four">
                <div class="footer__box">
                    <p class="text-reg">Скопич А.А.</p>
                    <p class="text-small text-small_border">расшифровка подписи</p>
                </div>
            </div>
        </div>
        <div class="footer__row footer__row_under">
            <div class="footer__col one_under">
                <p class="text-reg_one">Главный (старший) бухгалтер</p>
                <p class="text-reg_two text-reg_initials">М.П.</p>
                <div class="footer__seal">
                    <?=Html::img('@web/images/seal.jpg'); ?>
                </div>
            </div>
            <div class="footer__col two_under">
                <div class="footer__box footer__box_pt">
                    <p class="text-reg"></p>
                    <p class="text-small text-small_border">подпись</p>
                </div>
            </div>
            <div class="footer__col three_under">
                <div class="footer__box footer__box_pt">
                    <p class="text-reg"></p>
                    <p class="text-small text-small_border">расшифровка подписи</p>
                </div>
            </div>
        </div>
    </div>
</div>
