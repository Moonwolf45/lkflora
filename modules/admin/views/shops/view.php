<?php

use app\models\service\Service;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\shops\Shops */

$this->title = $model->address;
$this->params['breadcrumbs'][] = ['label' => 'Магазины', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this); ?>
<div class="shops-view">

    <h1><?= Html::encode($this->title); ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить данный элемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'address',
            [
                'attribute' => 'tariff_id',
                'format' => 'html',
                'value' => function($data) {
                    return $data->tariff->name;
                },
            ],
            [
                'attribute' => 'user_id',
                'format' => 'html',
                'value' => function($data) {
                    return $data->user->company_name;
                },
            ],
            [
                'attribute' => 'deleted',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->deleted) {
                        return '<p class="text-success">Да</p>';
                    } else {
                        return '<p class="text-danger">Нет</p>';
                    }
                },
            ],
        ]
    ]); ?>

    <?php
        $arrServiceModel = [];
        foreach ($model->services as $key => $service) {
            $arrServiceModel[$service->connection_date][$service->type_serviceId] = $service;
        }
    ?>


    <?php foreach ($arrServiceModel as $key => $arrService): ?>
        <h3>Дата подключения: <?= Yii::$app->formatter->asDate($key); ?></h3>

        <?php foreach ($arrService as $service): ?>
            <?= DetailView::widget([
                'model' => $service,
                'attributes' => [
                    [
                        'attribute' => 'name',
                        'label' => 'Название услуги',
                        'format' => 'html',
                        'value' => function($data) {
                            if ($data->type_service == Service::TYPE_TARIFF) {
                                return $data->tariff->name;
                            } else {
                                return $data->additions->name;
                            }
                        },
                    ],
                    [
                        'attribute' => 'type_service',
                        'label' => 'Услуга',
                        'format' => 'html',
                        'value' => function($data) {
                            if ($data['type_service'] == Service::TYPE_TARIFF) {
                                return 'Тариф';
                            } else {
                                return 'Доп. услуга';
                            }
                        }
                    ],
                    [
                        'attribute' => 'agree',
                        'label' => 'Подтверждён',
                        'format' => 'html',
                        'value' => function($data) {
                            if ($data->agree) {
                                return '<p class="text-success">Да</p>';
                            } else {
                                return '<p class="text-warning">Нет</p>';
                            }
                        }
                    ]
                ]
            ]); ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
</div>
