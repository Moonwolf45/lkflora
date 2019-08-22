<?php

use app\models\db\User;
use app\models\shops\Shops;
use app\models\tariff\Tariff;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\shops\ShopsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Магазины';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shops-index">

    <h1><?= Html::encode($this->title); ?></h1>

    <p>
        <?= Html::a('Создать магазин', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

<!--    --><?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'address',
            [
                'attribute' => 'tariff_id',
                'filter' => ArrayHelper::map(Tariff::find()->all(), 'id', 'name'),
                'format' => 'html',
                'value' => function($data) {
                    return $data->tariff->name;
                },
            ],
            [
                'attribute' => 'user_id',
                'filter' => ArrayHelper::map(User::find()->all(), 'id', 'company_name'),
                'format' => 'html',
                'value' => function($data) {
                    return $data->user->company_name;
                },
            ],
            [
                'attribute' => 'deleted',
                'filter' => [Shops::DELETED_TRUE => 'Да', Shops::DELETED_FALSE => 'Нет'],
                'format' => 'html',
                'value' => function($data) {
                    if ($data->deleted) {
                        return '<p class="text-success">Да</p>';
                    } else {
                        return '<p class="text-danger">Нет</p>';
                    }
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
