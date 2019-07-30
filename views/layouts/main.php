<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;

AppAsset::register($this); ?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset; ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link href="/images/favicon.ico" rel="shortcut icon" type="image/x-icon">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>
    <div>
        <section class="user-log">
            <div class="container container25">
                <div class="user-log__wrapp">
                    <div class="user-log__col">
                        <div class="user-log__block">
                            <div class="logo  user-log__logo">
                                <img class="logo__img" src="/images/logo.png" alt="">
                            </div>
                            <div class="user-log__desc">
                                <h2 class="user-log__title">Авторизация</h2>
                                <p class="user-log__text">Введите данные, указанные при регистрации</p>
                            </div>
                                <?=$content?>
                        </div>
                    </div>
                    <div class="user-log__col user-log__col_mobile">
                        <div class="user-log__image">
                            <img src="/images/authorization-img.jpg" alt="" class="user-log__img">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!--    <footer id="footer">-->
    <!--        <div class="container">-->
    <!--        </div>-->
    <!--    </footer>-->
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>