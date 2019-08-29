<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\tariff\Tariff */
/* @var $additions app\models\addition\Addition */

$this->title = 'Изменение тарифа: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Тарифы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение'; ?>

<div class="tariff-update">

    <h1><?= Html::encode($this->title); ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'additions' => $additions,
    ]); ?>

</div>
