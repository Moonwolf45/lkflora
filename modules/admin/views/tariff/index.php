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

<!--    --><?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            [
                'attribute' => 'cost',
                'format' => ['decimal', 2],
                'headerOptions' => ['width' => '120'],
            ],
            [
                'attribute' => 'about',
                'filter' => false,
                'format' => 'html',
                'headerOptions' => ['width' => '300'],
            ],
            [
                'attribute' => 'drop',
                'filter' => [0 => "Нет", 1 => "Да"],
                'format' => 'html',
                'headerOptions' => ['width' => '120'],
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
                'filter' => [0 => "Выключен", 1 => "Включен"],
                'format' => 'html',
                'headerOptions' => ['width' => '120'],
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
