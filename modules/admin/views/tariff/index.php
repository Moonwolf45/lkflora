<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Тарифы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tariff-index">

    <h1><?= Html::encode($this->title); ?></h1>

    <p>
        <?= Html::a('Создать тариф', ['create'], ['class' => 'btn btn-success']); ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
