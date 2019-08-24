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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags(); ?>
    <title><?= Html::encode($this->title); ?></title>
    <?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody(); ?>

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
</body>
</html>
<?php $this->endPage(); ?>
