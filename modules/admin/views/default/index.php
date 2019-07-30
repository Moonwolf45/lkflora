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
        ],
    ]);
    NavBar::end(); ?>
</div>
