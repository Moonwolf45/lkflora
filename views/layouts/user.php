<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link href="favicon.png" rel="shortcut icon" type="image/x-icon"/>
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>

    <?php if(isset($_COOKIE['sidebar'])){
        if ($_COOKIE['sidebar'] == '1'){
            echo '<body class="js_active-sidebar">';
        }
    } else {
        echo '<body>';
    } ?>

    <?php $this->beginBody() ?>
    <div id="wrapper">
        <?php echo $this->render('//layouts/_parts/_navigations'); ?>
        <?= $content ?>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="css/dadata.css" rel="stylesheet" />
    <script src="js/jquery.suggestions.min.js"></script>
    <script src="js/359.js"></script>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>
