<?php

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

</div>
