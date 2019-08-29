<?php

use app\models\service\Service;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\payments\Payments */

$this->title = $model->id . ' - ' . $model->user->company_name . ' - ' . $model->shop->address;
$this->params['breadcrumbs'][] = ['label' => 'Запросы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this); ?>

<div class="addition-view">

    <h1><?= Html::encode($this->title); ?></h1>

    <p>
        <?= Html::a('Подтвердить', ['update', 'id' => $model->id, 'shop_id' => $model->shop_id], [
            'class' => 'btn btn-success'
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
                    return Service::getTypeService($data->type_service);
                },
            ],
            [
                'attribute' => 'type_serviceId',
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
                'attribute' => 'connection_date',
                'format' => 'html',
                'value' => function($data) {
                    return Yii::$app->formatter->asDate($data->connection_date);
                },
            ],
            [
                'attribute' => 'writeoff_amount',
                'format' => 'html',
                'value' => function($data) {
                    return Yii::$app->formatter->asDecimal($data->writeoff_amount, 2);
                },
            ],
            [
                'attribute' => 'repeat_service',
                'format' => 'html',
                'value' => function($data) {
                    return Service::getRepeatService($data->repeat_service);
                },
            ],
            [
                'attribute' => 'agree',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->agree) {
                        return '<p class="text-success">' . Service::getAgreeService($data->agree) . '</p>';
                    } else {
                        return '<p class="text-danger">' . Service::getAgreeService($data->agree) . '</p>';
                    }
                },
            ],
            [
                'attribute' => 'edit_description',
                'format' => 'html',
            ],
        ],
    ]) ?>

</div>
