<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\db\User */

$this->title = $model->email;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить этого пользователя без возможности восстановить его?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'email:email',
            [
                'attribute' => 'doc_num',
                'value' => function($userSettingsData) {
                    return $userSettingsData->doc_num;
                }
            ],
            [
                'attribute' => 'shops',
                'label' => 'Магазины',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->shops) {
                        $shop_string = '';
                        $i = 1;
                        foreach ($data->shops as $shop) {
                            $shop_string .= $i . ': ' . $shop->address . '<br>';
                            $i++;
                        }

                        return '<p class="text-success">' . $shop_string . '</p>';
                    } else {
                        return '<p class="text-danger">Не найдено не одного магазина</p>';
                    }
                }
            ]
        ],
    ]); ?>
    <?= DetailView::widget([
        'model' => $userSettingsData,
        'attributes' => [
            'doc_num',
            'type_org',
            'name_org',
            'ur_addr_org',
            'ogrn',
            'inn',
            'kpp',
            'bik_banka',
            'name_bank',
            'kor_schet',
            'rass_schet'
        ],
    ]); ?>

</div>
