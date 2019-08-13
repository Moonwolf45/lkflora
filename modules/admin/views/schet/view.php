<?php

use app\models\payments\Payments;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\payments\Payments */

$this->title = $model->id . ' - ' . $model->user->company_name . ' - ' . $model->shop->address;
$this->params['breadcrumbs'][] = ['label' => 'Выставленные счета', 'url' => ['index']];
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
            'invoice_number',
            [
                'attribute' => 'user_id',
                'format' => 'html',
                'value' => function($data) {
                    return $data->user->company_name;
                },
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

    <p>
        <?php if($model->status == Payments::STATUS_EXPOSED): ?>
            <?= Html::a('Оплачен', ['update', 'id' => $model->id, 'status' => Payments::STATUS_PAID], [
                'class' => 'btn btn-success'
            ]); ?>

            <?= Html::a('Отменить', ['update', 'id' => $model->id, 'status' => Payments::STATUS_CANCEL], [
                'class' => 'btn btn-danger'
            ]); ?>
        <?php endif; ?>
    </p>

</div>
