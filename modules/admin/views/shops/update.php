<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\shops\Shops */

$this->title = 'Изменение магазина: ' . $model->address;
$this->params['breadcrumbs'][] = ['label' => 'Магазины', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->address, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="shops-update">

    <h1><?= Html::encode($this->title); ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]); ?>

</div>
