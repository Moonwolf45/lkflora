<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\tariff\Tariff */
/* @var $additions app\models\addition\Addition */

$this->title = 'Создание тарифа';
$this->params['breadcrumbs'][] = ['label' => 'Тарифы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tariff-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'additions' => $additions,
    ]) ?>

</div>
