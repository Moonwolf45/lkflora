<?php

use app\models\payments\Payments;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\addition\Addition */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Реестр финансовых операций', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this); ?>

<div class="addition-view">

    <h1><?= Html::encode($this->title); ?></h1>

    <p>
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
            [
                'attribute' => 'user_id',
                'format' => 'html',
                'value' => function($data) {
                    return $data->user->company_name;
                },
            ],
            [
                'attribute' => 'shop_id',
                'format' => 'html',
                'value' => function($data) {
                    return $data->shop->address;
                },
            ],
            [
                'attribute' => 'service_id',
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
            'invoice_date:date',
            [
                'attribute' => 'description',
                'format' => 'html',
                'headerOptions' => ['width' => '140'],
                'value' => function($data) {
                    if ($data->type == Payments::TYPE_WRITEOFF) {
                        if (!empty($data->tariff)) {
                            return $data->description . ': ' . $data->tariff->name;
                        } else {
                            return $data->description . ': ' . $data->addition->name;
                        }
                    }
                }
            ],
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
        ],
    ]) ?>

</div>
