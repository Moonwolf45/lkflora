<?php

use app\models\db\User;
use app\models\payments\Payments;
use app\models\shops\Shops;
use yii\grid\ActionColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\payments\PaymentsFinanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Реестр финансовых операций';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="addition-index">

    <h1><?= Html::encode($this->title); ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'headerOptions' => ['width' => '40'],
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
                'attribute' => 'shop_id',
                'filter' => ArrayHelper::map(Shops::find()->all(), 'id', 'address'),
                'format' => 'html',
                'value' => function($data) {
                    return $data->shop->address;
                },
            ],
            [
                'attribute' => 'type_service',
                'filter' => [Payments::TYPE_SERVICE_TARIFF => "Тариф", Payments::TYPE_SERVICE_ADDITION => "Доп. Услуга"],
                'format' => 'html',
                'value' => function($data) {
                    if ($data->type == Payments::TYPE_WRITEOFF) {
                        if ($data->type_service == Payments::TYPE_SERVICE_TARIFF) {
                            return 'Тарифф';
                        } elseif ($data->type_service == Payments::TYPE_SERVICE_ADDITION) {
                            return 'Доп. Услуга';
                        }
                    }

                    return '';
                },
            ],
            [
                'attribute' => 'service_id',
                'filter' => false,
                'format' => 'html',
                'value' => function($data) {
                    if ($data->type == Payments::TYPE_WRITEOFF) {
                        if ($data->type_service == Payments::TYPE_SERVICE_TARIFF) {
                            return $data->tariff->name;
                        } elseif ($data->type_service == Payments::TYPE_SERVICE_ADDITION) {
                            return $data->addition->name;
                        }
                    }

                    return '';
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
                'filter' => [Payments::STATUS_PAID => "Оплачен", Payments::STATUS_CANCEL => "Отменён",
                    Payments::STATUS_EXPOSED => "Выставлен"],
                'format' => 'html',
                'headerOptions' => ['width' => '140'],
                'value' => function($data) {
                    if ($data->status == Payments::STATUS_PAID) {
                        return '<p class="text-success">Оплачен</p>';
                    } elseif ($data->status == Payments::STATUS_EXPOSED) {
                        return '<p class="text-warning">Выставлен</p>';
                    } else {
                        return '<p class="text-danger">Отменён</p>';
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
