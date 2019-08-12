<?php

use app\models\db\User;
use app\models\payments\Payments;
use app\models\shops\Shops;
use yii\grid\ActionColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\addition\AdditionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Реестр финансовых операций';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="addition-index">

    <h1><?= Html::encode($this->title); ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'user_id',
                'filter' => ArrayHelper::map(User::find()->all(), 'id', 'company_name'),
                'format' => 'html',
                'value' => function($data) {
                    return $data->user->company_name;
                },
            ],
            [
                'attribute' => 'shop_id',
                'filter' => ArrayHelper::map(Shops::find()->all(), 'id', 'address'),
                'format' => 'html',
                'value' => function($data) {
                    return $data->shop->address;
                },
            ],
            [
                'attribute' => 'service_id',
                'filter' => false,
                'format' => 'html',
                'value' => function($data) {
                    if ($data->type == Payments::TYPE_WRITEOFF) {
                        if (!empty($data->tariff)) {
                            return $data->tariff->name;
                        } else {
                            return $data->addition->name;
                        }
                    }
                },
            ],
            [
                'attribute' => 'type',
                'filter' => [Payments::TYPE_REFILL => "Зачисление", Payments::TYPE_WRITEOFF => "Списание"],
                'format' => 'html',
                'headerOptions' => ['width' => '140'],
                'value' => function($data) {
                    if ($data->type) {
                        return '<p class="text-success">Зачисление</p>';
                    } else {
                        return '<p class="text-danger">Списание</p>';
                    }
                }
            ],
            [
                'attribute' => 'way',
                'filter' => [Payments::WAY_CARD => "Карта", Payments::WAY_SCHET => "Счет", Payments::WAY_BALANCE => 'Баланс'],
                'format' => 'html',
                'headerOptions' => ['width' => '140'],
                'value' => function($data) {
                    if ($data->way == Payments::WAY_CARD) {
                        return '<p>Карта</p>';
                    } elseif ($data->way == Payments::WAY_SCHET) {
                        return '<p>Счет</p>';
                    } else {
                        return '<p>Баланс</p>';
                    }
                }
            ],
            'date:date',
            [
                'attribute' => 'status',
                'filter' => [Payments::STATUS_PAID => "Оплачен", Payments::STATUS_NOTPAID => "Неоплачен"],
                'format' => 'html',
                'headerOptions' => ['width' => '140'],
                'value' => function($data) {
                    if ($data->status) {
                        return '<p class="text-success">Оплачен</p>';
                    } else {
                        return '<p class="text-danger">Неоплачен</p>';
                    }
                }
            ],
            [
                'class' => ActionColumn::class,
                'template'=>'{view} {delete}',
            ]
        ],
    ]); ?>


</div>
