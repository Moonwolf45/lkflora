<?php

use app\models\tariff\Tariff;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\tariff\Tariff */

$this->title = 'Тариф: ' . $model->name;
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
                        return '<p class="text-success">' . Tariff::getDrop($data->drop) . '</p>';
                    } else {
                        return '<p class="text-danger">' . Tariff::getDrop($data->drop) . '</p>';
                    }
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->status) {
                        return '<p class="text-success">' . Tariff::getStatus($data->status) . '</p>';
                    } else {
                        return '<p class="text-danger">' . Tariff::getStatus($data->status) . '</p>';
                    }
                }
            ],
            [
                'attribute' => 'maximum',
                'format' => 'html',
                'headerOptions' => ['width' => '140'],
                'value' => function($data) {
                    if ($data->maximum) {
                        return '<p class="text-success">Да</p>';
                    } else {
                        return '<p class="text-danger">Нет</p>';
                    }
                }
            ],
            'term',
            [
                'attribute' => 'resolutionService',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->tariffAdditionQty) {
                        $string = '<p class="text-success">';
                        foreach ($data->tariffAdditionQty as $taQ) {
                            $string .= $data->additionQty[$taQ->addition_id]->name . ' - Количество: ';
                            if ($taQ->status_con == 0) {
                                $string .= 'Неограниченно<br>';
                            } else {
                                $string .= $taQ->status_con . '<br>';
                            }
                        }
                        $string .= '</p>';
                        return $string;
                    } else {
                        return '<p class="text-danger">В этом тарифе нельзя подключать доп услуги</p>';
                    }
                }
            ],
            [
                'attribute' => 'connectedService',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->tariffAddition) {
                        $string = '<p class="text-success">';
                        foreach ($data->tariffAddition as $taQ) {
                            $string .= $data->addition[$taQ->addition_id]->name . ' - Количество: ';
                            if ($taQ->quantity == 0) {
                                $string .= 'Неограниченно<br>';
                            } else {
                                $string .= $taQ->quantity . '<br>';
                            }
                        }
                        $string .= '</p>';
                        return $string;
                    } else {
                        return '<p class="text-danger">В этом тарифе нет доп. услуг подключенных по умолчанию</p>';
                    }
                }
            ],
        ],
    ]); ?>
</div>
