<?php

use app\models\db\User;
use app\models\service\Service;
use app\models\shops\Shops;
use kartik\select2\Select2;
use yii\grid\ActionColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\web\View;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\service\ServiceNotAgreeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Запросы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schet-index">

    <h1><?= Html::encode($this->title); ?></h1>

    <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
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
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'shop_id',
                        'data' => ArrayHelper::map(Shops::find()->all(), 'id', 'address'),
                        'options' => ['placeholder' => ''],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]),
                    'format' => 'html',
                    'value' => function($data) {
                        return $data->shop->address;
                    },
                ],
                [
                    'attribute' => 'type_service',
                    'filter' => Service::getTypeService(),
                    'headerOptions' => ['width' => '150'],
                    'format' => 'html',
                    'value' => function($data) {
                        return Service::getTypeService($data->type_service);
                    },
                ],
                [
                    'attribute' => 'type_serviceId',
                    'label' => 'Название услуги',
                    'filter' => false,
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
                    'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'connection_date'
                    ]),
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
                    'class' => ActionColumn::class,
                    'template'=>'{view}',
                ]
            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>

<?php
$script = <<< JS
    
JS;
$this->registerJs($script, View::POS_READY); ?>
