<?php

use adamasantares\sum2str\Sum2Str;
use yii\helpers\Html;

/** @var TYPE_NAME $model */
/** @var TYPE_NAME $user */

?>

<style>
    body{font-family:Roboto,sans-serif;font-weight:500;color:#000;background:#fff;}
    *{margin:0;padding:0;text-decoration:none;box-sizing:border-box;}
    h1{margin:0;}
    p{margin:0;}
    .container{position:relative;width:1190px;margin:0 auto;padding:0;}
    .text-big{font-size:20px;font-weight:bold;}
    .tac{text-align:center;}
    .face{float:left;width:100%;padding-bottom:20px;}
    .face__row{float:left;width:100%;}
    .one_row{padding-bottom:20px;}
    .face__col{float:left;}
    .one_col{width:15%;}
    .two_col{position:relative;width:30%;top:15px;}
    .three_col{width:9%;}
    .four_col{position:relative;width:27%;top:15px;}
    .five_col{width:18%;}
    .text,.text-midl,.text-small{font-size:14px;font-weight:bold;}
    .text-norm{font-size:14px;font-weight:bold;}
    .text-norm_pt5{padding-top:5px;}
    .text-midl, .text-small{font-weight:400;}
    .text-midl_pb{padding-bottom:10px;}
    .text-midl_pt15{padding-top:15px;}
    .text-midl_pl{padding-left:5px;}
    .text-small{font-size:12px;border-top:2px solid #000;text-align:center;}
    .ttu{text-transform:uppercase;}
    .border{border-bottom:2px solid #000;}
    .goods{border-collapse:collapse;width:100%;text-align:center;}
    .goods th,.goods td{border:1px solid #000;}
    .goods th:nth-child(1){width:5%;}
    .goods th:nth-child(2){width:40%;}
    .goods th:nth-child(3){width:8%;}
    .goods th:nth-child(4){width:10%;}
    .goods th:nth-child(5){width:20%;}
    .goods th:nth-child(6){width:17%;}
    .goods td{background:none;color:#000;font-weight:400;}
    .goods td:nth-child(2){text-align:left;padding-left:5px;}
    .goods__total{float:left;width:100%;text-align:right;padding:10px 0 25px 0;}
    .goods__col{float:right;width:100%;}
    .goods__text{float:left;display:inline-block;width:20%;vertical-align:middle;}
    .goods__text_pr{width:73.2%;padding-right:30px;}
    hr{background:#000;height:4px;margin-top:0;margin-bottom:25px;border:none;}
    .footer{float:left;width:100%;}
    .footer__row{margin:0 -15px;}
    .footer__col{position:relative;float:left;width:45%;padding:0 15px;}
    .text-norm_p2{padding:25px 0 10px 0;}
    .footer__box{float:left;width:50%;}
    .s-di-vam{display:inline-block;vertical-align:middle;}
    .footer__block{display:inline-block;position:relative;width:100%;}
    .one-down, .two-down{float:left;}
    .one-down{width:30%;margin-right:5px;}
    .two-down{width:65%;}
    .one_p{float:left;width:20%;}
    .two_p{float:left;width:75%;}
    .footer__item{display:inline-block;float:left;width:100%;}
    .item-first{float:left;width:20%;}
    .item-second{float:left;width:75%;border-bottom:2px solid #000;}
    .item-first p, .item-second p{margin:5px 0;}
    .item-second_bank{min-height:60px;}
    .footer__block_pb{padding-bottom:25px;}
    .footer__sign{width:20px;height:20px;}
    .footer__seal{float:left;width:55px;}
    .footer__seal img{max-width:100%;max-height:100%;width:auto;height:auto;}
    .one-down,.two-down{position:relative;}
    .one-down p:before,.two-down p:before{content:"/";background:#FFF;position:absolute;bottom:-4px;list-style:outside;}
</style>

<div class="container">
    <h1 class="text-big tac">АКТ № E<?php echo $model['invoice_number']; ?> от
        <?php echo Yii::$app->formatter->asDate($model['date']); ?>г.<br>
        приемки-сдачи выполненных работ (оказанных услуг)</h1>
    <div class="face">
        <div class="face__row one_row">
            <div class="face__col one_col">
                <p class="text-midl">Исполнитель</p>
            </div>
            <div class="face__col two_col">
                <p class="text-midl text-midl_pb">ООО "Софт Технолджи"</p>
                <p class="text-small">контрагент</p>
            </div>
            <div class="face__col three_col">
                <p class="text-midl">в лице</p>
            </div>
            <div class="face__col four_col">
                <p class="text-midl text-midl_pb">Скопич А.А.</p>
                <p class="text-small">должность, ФИО</p>
            </div>
            <div class="face__col five_col">
                <p class="text-midl">с одной стороны</p>
            </div>
        </div>
        <div class="face__row">
            <div class="face__col one_col">
                <p class="text-midl">И Заказчик</p>
            </div>
            <div class="face__col two_col">
                <p class="text-midl ttu text-midl_pb"><?= $user['userSetting']['name_org']; ?></p>
                <p class="text-small">контрагент</p>
            </div>
            <div class="face__col three_col">
                <p class="text-midl">в лице</p>
            </div>
            <div class="face__col four_col">
                <p class="text-midl text-midl_pb"></p>
                <p class="text-small">должность, ФИО</p>
            </div>
            <div class="face__col five_col">
                <p class="text-midl">с другой стороны</p>
            </div>
        </div>
        <p class="text-midl text-midl_pt15">составили настоящий акт о том, что Исполнитель выполнил, а Заказчик принял сдедующие работы (услуги):</p>
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
            <td>Техническое сопровождение</td>
            <td>1</td>
            <td>шт.</td>
            <td><?= Yii::$app->formatter->asDecimal($model['amount'], 2); ?></td>
            <td><?= Yii::$app->formatter->asDecimal($model['amount'], 2); ?></td>
        </tr>
    </table>
    <div class="goods__total">
        <div class="goods__col">
            <p class="goods__text goods__text_pr">Итого:</p>
            <p class="goods__text"><?= Yii::$app->formatter->asDecimal($model['amount'], 2); ?></p>
        </div>
        <div class="goods__col">
            <p class="goods__text goods__text_pr">В том числе НДС:</p>
            <p class="goods__text">Без НДС</p>
        </div>
    </div>
    <p class="text-midl text-midl_pt15">Общая стоимость выполненых работ (оказаных услуг), включая налоги сосотавила:</p>
    <p class="text-norm text-norm_pt5"><?= Sum2Str::toStr($model['amount']); ?></p>
    <hr>
    <p class="text-midl">Вышеперечисленные работы (услуги) выполнены в установленные сроки, в полном объёме и с надлежащим качеством.</p>
    <p class="text-midl">Претензий друг к другу стороны не имеют</p>
    <div class="footer">
        <div class="footer__row">
            <div class="footer__col">
                <p class="text-norm text-norm_p2 ttu">Исполнитель</p>
                <p class="text-midl tac border">Общество с ограниченной ответсвенностью "Софт технолоджи"</p>
                <div class="footer__block">
                    <div class="footer__box">
                        <p class="text-midl s-di-vam one_p">ИНН</p>
                        <p class="text-midl s-di-vam border two_p">5027142964</p>
                    </div>
                    <div class="footer__box">
                        <p class="text-midl text-midl_pl s-di-vam one_p">КПП</p>
                        <p class="text-midl s-di-vam border two_p">502701001</p>
                    </div>
                </div>
                <div class="footer__item">
                    <div class="item-first">
                        <p class="text-midl">Адрес</p>
                    </div>
                    <div class="item-second item-second_address">
                        <p class="text-midl">140006, Московская обл., Люберецкий р-н, Люберци г, Котельнический проезд, Дом № 29А</p>
                    </div>
                </div>
                <div class="footer__item">
                    <div class="item-first">
                        <p class="text-midl">Р/с</p>
                    </div>
                    <div class="item-second">
                        <p class="text-midl">40702810170010025801</p>
                    </div>
                </div>
                <div class="footer__item">
                    <div class="item-first">
                        <p class="text-midl">К/с</p>
                    </div>
                    <div class="item-second">
                        <p class="text-midl">30101810645250000092</p>
                    </div>
                </div>
                <div class="footer__item">
                    <div class="item-first">
                        <p class="text-midl">Банк</p>
                    </div>
                    <div class="item-second item-second_bank">
                        <p class="text-midl ttu">Московский филиал АО КБ <br>"модульбанк"</p>
                    </div>
                </div>
                <div class="footer__item">
                    <div class="item-first">
                        <p class="text-midl">Бик</p>
                    </div>
                    <div class="item-second">
                        <p class="text-midl">044525092</p>
                    </div>
                </div>
                <div class="footer__item">
                    <div class="item-first">
                        <p class="text-midl">Телефон</p>
                    </div>
                    <div class="item-second">
                        <p class="text-midl">+7 (495)215-28-12</p>
                    </div>
                </div>
                <div class="footer__block footer__block_pb">
                    <div class="one-down border">
                        <p class="text-midl">/<?= Html::img('@web/images/sign.png', ['class' => 'footer__sign']); ?></p>
                    </div>
                    <div class="two-down border">
                        <p class="text-midl ">/</p>
                    </div>
                </div>
                <p class="text-midl tac">М.П.</p>
                <div class="footer__seal">
                    <?= Html::img('@web/images/seal.jpg'); ?>
                </div>
            </div>
            <div class="footer__col">
                <p class="text-norm text-norm_p2 ttu">Заказчик</p>
                <p class="text-midl ttu tac border"><?= $user['userSetting']['name_org']; ?></p>
                <div class="footer__block">
                    <div class="footer__box">
                        <p class="text-midl s-di-vam one_p">ИНН</p>
                        <p class="text-midl s-di-vam border two_p"><?= $user['userSetting']['inn']; ?></p>
                    </div>
                    <div class="footer__box">
                        <p class="text-midl text-midl_pl s-di-vam one_p">КПП</p>
                        <p class="text-midl s-di-vam border two_p"><?= $user['userSetting']['kpp']; ?></p>
                    </div>
                </div>
                <div class="footer__item">
                    <div class="item-first">
                        <p class="text-midl">Адрес</p>
                    </div>
                    <div class="item-second item-second_address">
                        <p class="text-midl"><?= $user['userSetting']['ur_addr_org']; ?></p>
                    </div>
                </div>
                <div class="footer__item">
                    <div class="item-first">
                        <p class="text-midl">Р/с</p>
                    </div>
                    <div class="item-second">
                        <p class="text-midl"><?= $user['userSetting']['rass_schet']; ?></p>
                    </div>
                </div>
                <div class="footer__item">
                    <div class="item-first">
                        <p class="text-midl">К/с</p>
                    </div>
                    <div class="item-second">
                        <p class="text-midl"><?= $user['userSetting']['kor_schet']; ?></p>
                    </div>
                </div>
                <div class="footer__item">
                    <div class="item-first">
                        <p class="text-midl">Банк</p>
                    </div>
                    <div class="item-second item-second_bank">
                        <p class="text-midl ttu"><?= $user['userSetting']['name_bank']; ?></p>
                    </div>
                </div>
                <div class="footer__item">
                    <div class="item-first">
                        <p class="text-midl">Бик</p>
                    </div>
                    <div class="item-second">
                        <p class="text-midl"><?= $user['userSetting']['bik_banka']; ?></p>
                    </div>
                </div>
                <div class="footer__item">
                    <div class="item-first">
                        <p class="text-midl">Телефон</p>
                    </div>
                    <div class="item-second">
                        <p class="text-midl"><?= $user['phone']; ?></p>
                    </div>
                </div>
                <div class="footer__block footer__block_pb">
                    <div class="one-down border">
                        <p class="text-midl ">/</p>
                    </div>
                    <div class="two-down border">
                        <p class="text-midl ">/</p>
                    </div>
                </div>
                <p class="text-midl tac">М.П.</p>
            </div>
        </div>
    </div>
</div>
