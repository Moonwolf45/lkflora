<?php

use app\models\db\User;
use app\models\payments\Payments;
use yii\grid\ActionColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\payments\PaymentsSchetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Выставленные счета';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schet-index">

    <h1><?= Html::encode($this->title); ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'invoice_number',
                'format' => 'html',
                'headerOptions' => ['width' => '90'],
            ],
            [
                'attribute' => 'user_id',
                'filter' => ArrayHelper::map(User::find()->all(), 'id', 'company_name'),
                'format' => 'html',
                'headerOptions' => ['width' => '120'],
                'value' => function($data) {
                    return $data->user->company_name;
                },
            ],
            [
                'attribute' => 'date',
                'format' => 'date',
                'headerOptions' => ['width' => '100'],
            ],
            [
                'attribute' => 'invoice_date',
                'format' => 'date',
                'headerOptions' => ['width' => '110'],
            ],
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
            [
                'class' => ActionColumn::class,
                'template' => '{view} {delete}',
            ]
        ],
    ]); ?>


</div>
