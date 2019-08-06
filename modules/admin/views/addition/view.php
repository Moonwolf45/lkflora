<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\addition\Addition */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Доп. услуги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this); ?>

<div class="addition-view">

    <h1><?= Html::encode($this->title); ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить данный элемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'cost',
                'format' => ['decimal', 2],
                'headerOptions' => ['width' => '100'],
            ],
            'about:html',
            [
                'attribute' => 'type',
                'format' => 'html',
                'headerOptions' => ['width' => '140'],
                'value' => function($data) {
                    if ($data->type) {
                        return '<p>Фиксированный платёж</p>';
                    } else {
                        return '<p>Ежемесячный платёж</p>';
                    }
                }
            ],
        ],
    ]) ?>

</div>
