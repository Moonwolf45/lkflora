<?php

use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $otherRates app\models\tariff\Tariff */
/* @var $searchModel */

$this->title = 'Тарифы';
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="tariff-index">

    <h1><?= Html::encode($this->title); ?></h1>

    <p>
        <?= Html::a('Создать тариф', ['create'], ['class' => 'btn btn-success']); ?>
    </p>

    <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'id',
                    'headerOptions' => ['width' => '30'],
                ],
                [
                    'attribute' => 'name',
                    'headerOptions' => ['width' => '100'],
                ],
                [
                    'attribute' => 'cost',
                    'format' => ['decimal', 2],
                    'headerOptions' => ['width' => '100'],
                ],
                [
                    'attribute' => 'about',
                    'filter' => false,
                    'format' => 'html',
                    'headerOptions' => ['width' => '250'],
                ],
                [
                    'attribute' => 'drop',
                    'filter' => [0 => "Нет", 1 => "Да"],
                    'format' => 'html',
                    'headerOptions' => ['width' => '120'],
                    'value' => function($data) {
                        if ($data->drop) {
                            return '<p class="text-success">Да</p>';
                        } else {
                            return '<p class="text-danger">Нет</p>';
                        }
                    }
                ],
                [
                    'attribute' => 'status',
                    'filter' => [0 => "Выключен", 1 => "Включен"],
                    'format' => 'html',
                    'headerOptions' => ['width' => '120'],
                    'value' => function($data) {
                        if ($data->status) {
                            return '<p class="text-success">Включен</p>';
                        } else {
                            return '<p class="text-danger">Выключен</p>';
                        }
                    }
                ],
                [
                    'attribute' => 'maximum',
                    'filter' => [0 => "Нет", 1 => "Да"],
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
                [
                    'attribute' => 'term',
                    'headerOptions' => ['width' => '120'],
                ],
                [
                    'class' => ActionColumn::class,
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'delete' => function ($url, $model, $key) {
                            if (count($model->shops) > 0) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', false, [
                                        'data-toggle' => 'modal', 'data-target' => '#myModal_' . md5($model->id)]);
                            } else {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                    'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                                    'data-method' => 'post',
                                    'data-pjax' => '0',
                                ]);
                            }
                        },
                    ],
                ]
            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>

<?php foreach ($otherRates as $otherRate): ?>
    <div class="modal fade" id="myModal_<?= md5($otherRate['id']); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    <h4 class="modal-title" id="myModalLabel">Удаление тарифа: <?= $otherRate['name']; ?></h4>
                </div>
                <form action="<?= Url::to(['/admin/tariff/modal-delete']); ?>" method="POST">
                    <div class="modal-body">
                        <p>Вы не можете удалить данный тариф, так как он назначен на одном или более магазинах. Пожалуйста выберите на какой тариф перевести магазины.</p>

                        <input type="hidden" name="oldTariff_id" value="<?= $otherRate['id']; ?>">
                        <label class="field__text" for="shops-tariff_id">Выберите новый тариф:</label>
                        <select class="form-control" name="otherRate">
                            <?php foreach ($otherRates as $rate): ?>
                                <?php if ($rate['id'] != $otherRate['id']): ?>
                                    <option value="<?= $rate['id']; ?>"><?= $rate['name']; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-default" data-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-success">Перевести</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php

$script = <<< JS
    $('.modal-footer .btn-success').on('click', function() {
        $('.background-loader').show();
    });
JS;

//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, View::POS_READY);

?>
