<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Tariff */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Тарифы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this); ?>

<div class="tariff-view">

    <h1><?= Html::encode($this->title); ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить данный элемент?',
                'method' => 'post',
            ],
        ]); ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'cost',
                'format' => ['decimal', 2],
                'headerOptions' => ['width' => '120'],
            ],
            'about:html',
            [
                'attribute' => 'drop',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->drop) {
                        return '<p class="text-success">Да</p>';
                    } else {
                        return '<p class="text-danger">Нет</p>';
                    }
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->status) {
                        return '<p class="text-success">Включен</p>';
                    } else {
                        return '<p class="text-danger">Выключен</p>';
                    }
                }
            ],
            'term:date',
        ],
    ]); ?>
</div>
