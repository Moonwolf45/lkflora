<?php

use yii\helpers\Html;

?>

<div class="content">
    <h2 class="content__title">Сообщения</h2>
    <div class="content__row">
        <div class="content__col-9 content__col-9_messages">
            <!-- СОЗДАНИЕ ОБРАЩЕНИЯ В ТЕХПОДДЕРЖКУ -->
<!--            <div class="content__box">-->
<!--                <div class="appeal">-->
<!--                    <div class="appeal__wrapp appeal__wrapp_mw635 appeal__wrapp_p2">-->
<!--                        <div class="little-title">Создание обращения в техподдержку</div>-->
<!--                        <div class="declaration">-->
<!--                            <p class="declaration__text">На бесплатном тарифе Техподдержка работает с пн-пт с 10.00 до 19.00</p>-->
<!--                            <a href="#" class="declaration__link">перейти на платный тариф</a>-->
<!--                        </div>-->
<!--                        <form action="" class="appeal__form">-->
<!--                            <div class="appeal__block">-->
<!--                                <div class="field">-->
<!--                                    <p class="field__text">тема обращения</p>-->
<!--                                </div>-->
<!--                                <input type="text">-->
<!--                                <div class="jsx-select input choose__name choose__name-appeal">-->
<!--                                    <span class="jsx-select__selected">Выберите тему</span>-->
<!--                                    <ul class="jsx-select__list choose__list">-->
<!--                                        <li class="choose__item">-->
<!--                                            Как собрать букет-->
<!--                                        </li>-->
<!--                                        <li class="choose__item">-->
<!--                                            Как букет выбрать-->
<!--                                        </li>-->
<!--                                        <li class="choose__item">-->
<!--                                            Какие есть виды букетов-->
<!--                                        </li>-->
<!--                                    </ul>-->
<!--                                </div>-->
<!--                                <textarea name="" class="textarea textarea_mb20" cols="30" rows="20" placeholder="Введите текст сообщения"></textarea>-->
<!--                                <div class="attach">-->
<!--                                    <div class="attach__wrapp-label">-->
<!--                                        <label class="attach__label"  id="label-file1" for="file1"><input class="left clip-input attach__input" type="file" name="file_name" id="file1">-->
<!--                                        <span class="attach__icon s-di-vertical-m"></span>-->
<!--                                        <span class="attach__text s-di-vertical-m clip-input-txt">Прикрепить файл</span>-->
<!--                                        </label>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <button class="button button_width-200px appeal__button">Отправить</button>-->
<!--                        </form>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->

            <!-- СОЗДАНИЕ ОБРАЩЕНИЯ В ТЕХПОДДЕРЖКУ -->

            <div class="content__box">
                <div class="discussion">
                    <div class="discussion__content">
                        <ul class="discussion__list">
                            <li class="discussion__item discussion__item_right">
                                <div class="discussion__data-message">
                                    <p class="discussion__name">ИП Петров Алексей Викторович</p>
                                    <div class="discussion__message" >
                                        <div class="discussion__sms" data-time-sms="18:54">
                                            <p>
                                                Hello John, thank you for calling Provide Support. How may I help you?
                                            </p>
                                        </div>
                                        <div class="discussion__avatar">
                                            <?= Html::img('@web/images/user-photo.png', ['class' => 'discussion__img']); ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="discussion__item discussion__item_left">
                                <div class="discussion__data-message">
                                    <p class="discussion__name">Техподдержка</p>
                                    <div class="discussion__message" >
                                        <div class="discussion__sms" data-time-sms="18:56">
                                            <p>
                                                Please hold for one moment, I'll check with my manager.
                                            </p>
                                        </div>
                                        <div class="discussion__avatar">
                                            <?= Html::img('@web/images/icon/icon_QA.png', ['class' => 'discussion__img']); ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="discussion__item discussion__item_right">
                                <div class="discussion__data-message">
                                    <p class="discussion__name">ИП Петров Алексей Викторович</p>
                                    <div class="discussion__message" >
                                        <div class="discussion__sms" data-time-sms="18:54">
                                            <p>
                                                I'm sorry, I don't have the answer to that question. May I put you on hold for a few minutes while I check with my manager?
                                            </p>
                                        </div>
                                        <div class="discussion__avatar">
                                            <?= Html::img('@web/images/user-photo.png', ['class' => 'discussion__img']); ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="discussion__item discussion__item_left">
                                <div class="discussion__data-message">
                                    <p class="discussion__name">Техподдержка</p>
                                    <div class="discussion__message" >
                                        <div class="discussion__sms" data-time-sms="18:56">
                                            <p>
                                                Please hold for one moment, I'll check with my manager.
                                            </p>
                                        </div>
                                        <div class="discussion__avatar">
                                            <?= Html::img('@web/images/icon/icon_QA.png', ['class' => 'discussion__img']); ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="discussion__item discussion__item_left">
                                <div class="discussion__data-message">
                                    <p class="discussion__name">Техподдержка</p>
                                    <div class="discussion__message" >
                                        <div class="discussion__sms" data-time-sms="18:56">
                                            <p>
                                                Please hold for one moment, I'll check with my manager.
                                            </p>
                                        </div>
                                        <div class="discussion__avatar">
                                            <?= Html::img('@web/images/icon/icon_QA.png', ['class' => 'discussion__img']); ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="discussion__item discussion__item_right">
                                <div class="discussion__data-message">
                                    <p class="discussion__name">ИП Петров Алексей Викторович</p>
                                    <div class="discussion__message" >
                                        <div class="discussion__sms" data-time-sms="18:54">
                                            <p>
                                                I'm sorry, I don't have the answer to that question. May I put you on hold for a few minutes while I check with my manager?
                                            </p>
                                        </div>
                                        <div class="discussion__avatar">
                                            <?= Html::img('@web/images/user-photo.png', ['class' => 'discussion__img']); ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="appeal">
                    <div class="appeal__wrapp appeal__wrapp_pt30 appeal__wrapp_mw655 appeal__wrapp_mb30">
                        <form action="" class="appeal__form">
                            <div class="appeal__block">
                                <textarea name="" class="textarea textarea_mb20" cols="30" rows="20" placeholder="Введите текст сообщения"></textarea>
                                <div class="attach">
                                    <div class="attach__wrapp-label">
                                        <label class="attach__label"  id="label-file1" for="file1"><input class="left clip-input attach__input" type="file" name="file_name" id="file1">
                                            <span class="attach__icon s-di-vertical-m"></span>
                                            <span class="attach__text s-di-vertical-m clip-input-txt">Прикрепить файл</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <button class="button button_width-200px appeal__button">Отправить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="content__col-3 content__col-3-messages">
            <div class="content__box content__box-messages">
                <div class="support">
                    <div class="support__wrapp">
                        <div class="add-something add-something-support d-f-between">
                            <p class="add-something__text add-something__text_fs14 add-something__text-support">Обращения</p>
                            <div class="add-something__plus"></div>
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
