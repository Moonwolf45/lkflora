<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\assets\AdminAsset;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

AdminAsset::register($this); ?>

<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language; ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="apple-touch-icon" sizes="57x57" href="/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="/images/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/images/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <?= Html::csrfMetaTags(); ?>
    <title><?= Html::encode($this->title); ?></title>
    <?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody(); ?>

<div class="background-loader">
    <div class="loader"></div>
</div>
<div class="wrap">
    <?php NavBar::begin([
        'brandLabel' => "Админ-панель FloraPoint",
        'brandUrl'   => "/admin",
        'options'    => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                ['label' => 'Главная админки', 'url' => ['/admin']],
                ['label' => 'Пользователи', 'url' => ['/admin/user']],
                ['label' => 'Тарифы', 'url' => ['/admin/tariff']],
                ['label' => 'Магазины', 'url' => ['/admin/shops']],
                ['label' => 'Доп. услуги', 'url' => ['/admin/addition']],
                ['label' => 'Реестр фин. оп.', 'url' => ['/admin/finance']],
                ['label' => 'Выставленные счета', 'url' => ['/admin/schet']],
                ['label' => 'Запросы', 'url' => ['/admin/need']],
            ],
        ]);

    NavBar::end(); ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'Главная', 'url' => '/admin'],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]); ?>
        <?= Alert::widget(); ?>
        <?= $content; ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-right"><a href="<?php echo Url::to(['/user/logout']); ?>"> Выход</a></p>
    </div>
</footer>

<?php $this->endBody(); ?>

<script>
    $(document).ready(function () {
        $(document).on('pjax:complete', function() {
            $('.background-loader').hide();
        });

        $(document).on('pjax:send', function() {
            $('.background-loader').show();
        });
    });
</script>
</body>
</html>
<?php $this->endPage(); ?>
