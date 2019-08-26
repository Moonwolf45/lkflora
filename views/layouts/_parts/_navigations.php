<?php

use app\models\db\User;
use yii\helpers\Html;
use yii\helpers\Url;

$currentPage = Yii::$app->requestedRoute;

$avatar = '';
if (Yii::$app->user->identity->avatar) {
    $avatar = Yii::$app->user->identity->avatar;
} else {
    $avatar = 'images/group.svg';
}

$company_name = '';
if (Yii::$app->user->identity->company_name) {
    $company_name = Yii::$app->user->identity->company_name;
}

$cook_sidebar = '';
if (isset($_COOKIE['sidebar'])) {
    if ($_COOKIE['sidebar'] == '1') {
        $cook_sidebar = 'active';
    }
}

$active_one = '';
$active_two = '';
$active_tree = '';
$active_four = '';
$active_five = '';
$active_six = '';
$active_seven = '';
switch ($currentPage) {
    case('user/index'):
        $active_one = 'active';
    break;

    case('user/account'):
        $active_two = 'active';
    break;

    case('user/payment'):
        $active_tree = 'active';
    break;

    case('user/tickets'):
        $active_four = 'active';
    break;

    case('user/'):
        $active_five = 'active';
    break;

    case('user/settings'):
        $active_six = 'active';
    break;

    case('user/'):
        $active_seven = 'active';
    break;

    default:
    break;
}

?>

<header class="header">
    <div class="header__wrapp">
        <div class="logo header__logo">
            <a href="<?=Url::to(['/user/index']); ?>">
                <?=Html::img('@web/images/logo.png', ['class' => 'logo__img']); ?>
            </a>
        </div>
        <div class="burger header__burger js_burger <?=$cook_sidebar; ?>">
            <div class="burger__box">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="header__title-mobile">
            Анкета
        </div>
        <ul class="header__list">
<!--            <li class="header__item">-->
<!--                <a href="" class="notification-data">-->
<!--                    --><?//=Html::img('@web/images/icon/icon-notifications.svg'); ?>
<!--                </a>-->
<!--            </li>-->
            <li class="header__item header__item_mobile-none">
                <a href="<?= Url::to(['/user/tickets']);?>" class="massage-data">
                    <?=Html::img('@web/images/icon/icon-messages.svg'); ?>
                </a>
            </li>
            <li class="header__item header__item_mobile-none">
                <div class="user-data">
                    <a class="user-data__avatar">
                        <?=Html::img('@web/' . $avatar, ['class' => 'user-data__img']); ?>
                    </a>
                    <div class="user-data__info">
                        <p class="user-data__name"><?php echo $company_name; ?></p>
                        <div class="user-data__balance">
                            <p class="user-data__cash">баланс:
                                <span><?= Yii::$app->formatter->asDecimal(Yii::$app->user->identity->balance, 2); ?> руб</span>
                            </p>
                            <a href="<?php echo Url::to(['/user/payment', 'd' => 1, 'i' => 1]); ?>" class="user-data__add-cash">
                                <?=Html::img('@web/images/icon/icon-add.svg'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</header>

<div class="sidebar">
    <div class="burger sidebar__burger js_burger <?=$cook_sidebar; ?>">
        <div class="burger__box">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar__mobile-block">
        <div class="sidebar__mobile-close-logo">
            <div class="close-menu"></div>
            <div class="logo sidebar__logo">
                <?=Html::img('@web/images/logo.png', ['class' => 'logo__img']); ?>
            </div>
        </div>
        <div class="user-data">
            <div class="user-data__box-mobile">
                <a class="user-data__avatar">
                    <?=Html::img('@web/' . $avatar, ['class' => 'user-data__img']); ?>
                </a>
                <div class="user-data__info">
                    <p class="user-data__name"><?php echo $company_name; ?></p>
                </div>
            </div>
            <div class="user-data__balance">
                <p class="user-data__cash">баланс:
                    <span><?= Yii::$app->formatter->asDecimal(Yii::$app->user->identity->balance, 2); ?> руб</span>
                </p>
                <a href="<?php echo Url::to(['/user/payment', 'd' => 1, 'i' => 1]); ?>" class="user-data__add-cash">
                    <?=Html::img('@web/images/icon/icon-add.svg'); ?>
                </a>
            </div>
        </div>
    </div>
    <div class="sidebar__wrapp">
        <div class="sidebar__scroll">
            <ul class="menu sidebar__menu">
                <li class="menu__item">
                    <a href="<?=Url::to(['/user/index']); ?>" class="menu__link <?= $active_one; ?>" title="Главная">
                        <div class="menu__box">
                            <span class="menu__icon">
                                <?=Html::img('@web/images/icon/icon-main.svg'); ?>
                            </span>
                            <span class="menu__text">Главная</span>
                        </div>
                    </a>
                </li>
                <li class="menu__item">
                    <a href="<?php echo Url::to(['/user/account']); ?>" class="menu__link <?= $active_two; ?>" title="Анкета">
                        <div class="menu__box">
                            <span class="menu__icon">
                                <svg width="80" height="24" viewBox="0 0 21 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.1203 12.9319C10.1451 12.9319 10.17 12.9319 10.1998 12.9319C10.2097 12.9319 10.2197 12.9319 10.2296 12.9319C10.2445 12.9319 10.2644 12.9319 10.2793 12.9319C11.7355 12.907 12.9134 12.3951 13.7831 11.416C15.6966 9.25906 15.3785 5.5614 15.3437 5.20853C15.2195 2.55954 13.967 1.29219 12.9333 0.700766C12.1629 0.258439 11.2634 0.0198799 10.2594 0H10.2246C10.2197 0 10.2097 0 10.2048 0H10.1749C9.62326 0 8.53981 0.0894595 7.50109 0.680886C6.45739 1.27231 5.18508 2.53966 5.06083 5.20853C5.02604 5.5614 4.70796 9.25906 6.6214 11.416C7.48618 12.3951 8.66406 12.907 10.1203 12.9319ZM6.38781 5.33278C6.38781 5.31787 6.39278 5.30296 6.39278 5.29302C6.55679 1.72955 9.08651 1.34686 10.17 1.34686H10.1898C10.1998 1.34686 10.2147 1.34686 10.2296 1.34686C11.5715 1.37668 13.8527 1.92338 14.0068 5.29302C14.0068 5.30793 14.0068 5.32284 14.0118 5.33278C14.0167 5.36757 14.3646 8.74715 12.7842 10.5264C12.1579 11.2321 11.323 11.58 10.2246 11.59C10.2147 11.59 10.2097 11.59 10.1998 11.59C10.1898 11.59 10.1849 11.59 10.1749 11.59C9.08154 11.58 8.24161 11.2321 7.62036 10.5264C6.04488 8.75709 6.38284 5.3626 6.38781 5.33278Z" fill="#95B19E"/>
                                    <path d="M20.413 19.0648C20.413 19.0598 20.413 19.0549 20.413 19.0499C20.413 19.0101 20.4081 18.9704 20.4081 18.9256C20.3782 17.9416 20.3136 15.6405 18.1567 14.9049C18.1418 14.9 18.1219 14.895 18.107 14.89C15.8655 14.3185 14.0018 13.0263 13.9819 13.0114C13.6787 12.7976 13.2612 12.8722 13.0475 13.1754C12.8338 13.4785 12.9084 13.896 13.2115 14.1097C13.296 14.1694 15.2741 15.546 17.7491 16.1822C18.9071 16.5947 19.0363 17.8322 19.0711 18.9654C19.0711 19.0101 19.0711 19.0499 19.0761 19.0896C19.0811 19.5369 19.0513 20.2278 18.9717 20.6254C18.1666 21.0826 15.0107 22.6631 10.2097 22.6631C5.42856 22.6631 2.25275 21.0776 1.44264 20.6204C1.36313 20.2228 1.32834 19.532 1.33828 19.0847C1.33828 19.0449 1.34325 19.0052 1.34325 18.9604C1.37804 17.8273 1.50725 16.5897 2.66526 16.1772C5.1403 15.5411 7.11835 14.1594 7.20284 14.1048C7.50601 13.891 7.58056 13.4736 7.36685 13.1704C7.15314 12.8672 6.73567 12.7927 6.4325 13.0064C6.41262 13.0213 4.55882 14.3135 2.30742 14.885C2.28754 14.89 2.27263 14.895 2.25772 14.9C0.100752 15.6405 0.0361426 17.9416 0.00632274 18.9207C0.00632274 18.9654 0.00632262 19.0052 0.00135265 19.0449C0.00135265 19.0499 0.00135265 19.0549 0.00135265 19.0598C-0.00361732 19.3183 -0.00858718 20.6452 0.254821 21.3112C0.304521 21.4404 0.393981 21.5498 0.51326 21.6243C0.662359 21.7237 4.23577 24 10.2146 24C16.1935 24 19.7669 21.7188 19.916 21.6243C20.0303 21.5498 20.1248 21.4404 20.1745 21.3112C20.423 20.6502 20.418 19.3232 20.413 19.0648Z" fill="#95B19E"/>
                                </svg>
                            </span>
                            <span class="menu__text">Анкета</span>
                        </div>
                    </a>
                </li>
                <li class="menu__item">
                    <a href="<?php echo Url::to(['/user/payment', 'd' => 1, 'i' => 1]); ?>" class="menu__link <?= $active_tree; ?>" title="Финансы">
                        <div class="menu__box">
                            <span class="menu__icon">
                                <?=Html::img('@web/images/icon/icon-finance.svg'); ?>
                            </span>
                            <span class="menu__text">Финансы</span>
                        </div>
                    </a>
                </li>
                <li class="menu__item">
                    <a href="<?php echo Url::to(['/user/tickets']); ?>" class="menu__link <?= $active_four; ?>" title="Тех. поддержка">
                        <div class="menu__box">
                            <span class="menu__icon">
                                <?=Html::img('@web/images/icon/icon-question.svg'); ?>
                            </span>
                            <span class="menu__text">Тех. поддержка</span>
                        </div>
                    </a>
                </li>
<!--                <li class="menu__item">-->
<!--                    <a href="--><?php //echo Url::to(['']); ?><!--" class="menu__link --><?//= $active_five; ?><!--" title="Приложение">-->
<!--                        <div class="menu__box">-->
<!--                            <span class="menu__icon">-->
<!--                                --><?//=Html::img('@web/images/icon/icon-phone.svg'); ?>
<!--                            </span>-->
<!--                            <span class="menu__text">Приложение</span>-->
<!--                        </div>-->
<!--                    </a>-->
<!--                </li>-->
                <li class="menu__item">
                    <a href="<?php echo Url::to(['/user/settings']); ?>" class="menu__link <?= $active_six; ?>" title="Настройки">
                        <div class="menu__box">
                            <span class="menu__icon">
                                <?=Html::img('@web/images/icon/icon-settings.svg'); ?>
                            </span>
                            <span class="menu__text">Настройки</span>
                        </div>
                    </a>
                </li>
                <li class="menu__item">
                    <a href="<?php echo Url::to(['']); ?>" class="menu__link <?= $active_seven; ?>" title="Инструкции">
                        <div class="menu__box">
                            <span class="menu__icon">
                                <?=Html::img('@web/images/icon/icon-play.svg'); ?>
                            </span>
                            <span class="menu__text">Инструкции</span>
                        </div>
                    </a>
                </li>
                <?php if (Yii::$app->user->identity->role == User::ROLE_ADMIN): ?>
                    <li class="menu__item">
                        <a href="<?php echo Url::to(['/admin']); ?>" class="menu__link">
                            <div class="menu__box">
                                <span class="menu__icon">
                                    <svg width="80" height="56" viewBox="0 0 80 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M33.6 23.6C35.0076 23.6 36.1728 22.5544 36.368 21.2H48.8C49.0212 21.2 49.2 21.0212 49.2 20.8C49.2 20.5788 49.0212 20.4 48.8 20.4H36.368C36.1728 19.0456 35.0076 18 33.6 18C32.0564 18 30.8 19.2564 30.8 20.8C30.8 22.3436 32.0564 23.6 33.6 23.6ZM33.6 18.8C34.7028 18.8 35.6 19.6972 35.6 20.8C35.6 21.9028 34.7028 22.8 33.6 22.8C32.4972 22.8 31.6 21.9028 31.6 20.8C31.6 19.6972 32.4972 18.8 33.6 18.8Z" fill="#95B19E"/>
                                        <path d="M31.2 35.6H43.632C43.8272 36.9544 44.9924 38 46.4 38C47.9436 38 49.2 36.7436 49.2 35.2C49.2 33.6564 47.9436 32.4 46.4 32.4C44.9924 32.4 43.8272 33.4456 43.632 34.8H31.2C30.9788 34.8 30.8 34.9788 30.8 35.2C30.8 35.4212 30.9788 35.6 31.2 35.6ZM46.4 33.2C47.5028 33.2 48.4 34.0972 48.4 35.2C48.4 36.3028 47.5028 37.2 46.4 37.2C45.2972 37.2 44.4 36.3028 44.4 35.2C44.4 34.0972 45.2972 33.2 46.4 33.2Z" fill="#95B19E"/>
                                        <path d="M31.2 28.4H37.632C37.8272 29.7544 38.9924 30.8 40.4 30.8C41.8076 30.8 42.9728 29.7544 43.168 28.4H48.8C49.0212 28.4 49.2 28.2212 49.2 28C49.2 27.7788 49.0212 27.6 48.8 27.6H43.168C42.9728 26.2456 41.8076 25.2 40.4 25.2C38.9924 25.2 37.8272 26.2456 37.632 27.6H31.2C30.9788 27.6 30.8 27.7788 30.8 28C30.8 28.2212 30.9788 28.4 31.2 28.4ZM40.4 26C41.5028 26 42.4 26.8972 42.4 28C42.4 29.1028 41.5028 30 40.4 30C39.2972 30 38.4 29.1028 38.4 28C38.4 26.8972 39.2972 26 40.4 26Z" fill="#95B19E"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M50 17H30C29.4477 17 29 17.4477 29 18V38C29 38.5523 29.4477 39 30 39H50C50.5523 39 51 38.5523 51 38V18C51 17.4477 50.5523 17 50 17ZM30 16C28.8954 16 28 16.8954 28 18V38C28 39.1046 28.8954 40 30 40H50C51.1046 40 52 39.1046 52 38V18C52 16.8954 51.1046 16 50 16H30Z" fill="#95B19E"/>
                                    </svg>
                                </span>
                                <span class="menu__text">Админ-панель</span>
                            </div>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="menu__item">
                    <a href="<?php echo Url::to(['/site/logout']); ?>" class="menu__link menu__link_last" title="Выйти">
                        <div class="menu__box">
                            <span class="menu__icon">
                                <?=Html::img('@web/images/icon/icon-logout.svg'); ?>
                            </span>
                            <span class="menu__text">Выйти</span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
