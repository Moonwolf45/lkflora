<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

$this->title = 'Админ-панель FloraPoint'; ?>

<div class="admin-default-index">
    <?php NavBar::begin();
        echo Nav::widget([
            'options' => ['class' => ''],
            'items'   => [
                ['label' => 'Пользователи', 'url' => ['/admin/user']],
                ['label' => 'Тарифы', 'url' => ['/admin/tariff']],
                ['label' => 'Магазины', 'url' => ['/admin/shops']],
                ['label' => 'Доп. услуги', 'url' => ['/admin/addition']],
                ['label' => 'Реестр финансовых операций', 'url' => ['/admin/finance']],
                ['label' => 'Выставленные счета', 'url' => ['/admin/schets']],
            ],
        ]);
    NavBar::end(); ?>
</div>
