<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\addition\Addition */

$this->title = 'Создание услуги';
$this->params['breadcrumbs'][] = ['label' => 'Доп. услуги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="addition-create">

    <h1><?= Html::encode($this->title); ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]); ?>

</div>
