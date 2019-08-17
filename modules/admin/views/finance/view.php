<?php

use app\models\payments\Payments;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\payments\Payments */

$this->title = $model->id . ' - ' . $model->user->company_name . ' - ' . $model->shop->address;
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
                'attribute' => 'type_service',
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
                'attribute' => 'amount',
                'format' => 'html',
                'value' => function($data) {
                    return Yii::$app->formatter->asDecimal($data->amount, 2);
                },
            ],
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

                    return '';
                }
            ],
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
        ],
    ]) ?>

</div>
