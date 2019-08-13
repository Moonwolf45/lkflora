<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\addition\AdditionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Доп. услуги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="addition-index">

    <h1><?= Html::encode($this->title); ?></h1>

    <p>
        <?= Html::a('Создание услуги', ['create'], ['class' => 'btn btn-success']); ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
                'headerOptions' => ['width' => '100'],
            ],
            [
                'attribute' => 'about',
                'filter' => false,
                'format' => 'html',
                'headerOptions' => ['width' => '280'],
            ],
            [
                'attribute' => 'type',
                'filter' => [0 => "Фиксированный", 1 => "Ежемесячный"],
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>