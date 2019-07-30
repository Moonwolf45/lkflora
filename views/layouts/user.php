<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;

AppAsset::register($this); ?>

<?php $this->beginPage(); ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language; ?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset; ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link href="/images/favicon.ico" rel="shortcut icon" type="image/x-icon">
        <?php $this->registerCsrfMetaTags(); ?>
        <title><?= Html::encode($this->title); ?></title>
        <?php $this->head(); ?>
    </head>

    <?php $class = '';

    if(isset($_COOKIE['sidebar'])){
        if ($_COOKIE['sidebar'] == '1'){
            $class = 'class="js_active-sidebar"';
        }
    } ?>
    <body <?php echo $class; ?>>

        <?php $this->beginBody(); ?>
            <div id="wrapper">
                <?php echo $this->render('/layouts/_parts/_navigations'); ?>
                <?= $content; ?>
            </div>
        <?php $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage(); ?>
