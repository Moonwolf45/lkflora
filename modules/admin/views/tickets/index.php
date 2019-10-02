<?php

use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Тикеты';
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="tariff-index">

    <h1><?= Html::encode($this->title); ?></h1>

    <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'subject',
                [
                    'attribute' => 'new_text',
                    'filter' => [0 => "Да", 1 => "Нет"],
                    'format' => 'html',
                    'value' => function($data) {
                        if ($data->new_text) {
                            return '<p class="text-danger">Нет</p>';
                        } else {
                            return '<p class="text-success">Да</p>';
                        }
                    }
                ],
                [
                    'attribute' => 'status',
                    'filter' => [0 => "Закрыто", 1 => "Открыто"],
                    'format' => 'html',
                    'value' => function($data) {
                        if ($data->status) {
                            return '<p class="text-success">Открыто</p>';
                        } else {
                            return '<p class="text-danger">Закрыто</p>';
                        }
                    }
                ],
                [
                    'class' => ActionColumn::class,
                    'template'=>'{view}{update}{delete}',
                ]
            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>
