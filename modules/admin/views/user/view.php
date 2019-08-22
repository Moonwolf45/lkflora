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
            'phone',
            [
                'attribute' => 'shops',
                'label' => 'Магазины',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->shops) {
                        $shop_string = '';
                        $i = 1;
                        foreach ($data->shops as $shop) {
                            $shop_string .= $i . ': ' . $shop['address'] . ', Тариф: ' . $shop['tariff']['name'] . '<br>';

                            if (!empty($shop['shopsAdditions']) && !empty($shop['additions'])) {
                                $shops_addition = [];
                                foreach ($shop['shopsAdditions'] as $shop_ad) {
                                    $shops_addition[$shop_ad['shop_id'] . '_' . $shop_ad['addition_id']] = $shop_ad;
                                }
                                foreach ($shop['additions'] as $addition) {
                                    $shop_string .= '&nbsp;&nbsp;- ' . $addition['name'] . ', Кол-во: ' . $shops_addition[$shop['id'] . '_' . $addition['id']]['quantity'] .  '<br>';
                                }
                            }
                            $i++;
                        }
                        return '<p class="text-success">' . $shop_string . '</p>';
                    } else {
                        return '<p class="text-danger">Не найдено не одного магазина</p>';
                    }
                }
            ],
            [
                'attribute' => 'doc_num',
                'label' => 'Номер договора',
                'format' => 'html',
                'value' => function($data) {
                    return $data->userSetting->doc_num;
                }
            ],
            [
                'attribute' => 'type_org',
                'label' => 'Тип организации',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->userSetting->type_org == 'ip') {
                        return 'ИП';
                    } else {
                        return 'ООО/АО/ЗАО';
                    }
                }
            ],
            [
                'attribute' => 'name_org',
                'label' => 'Название организации',
                'format' => 'html',
                'value' => function($data) {
                    return $data->userSetting->name_org;
                }
            ],
            [
                'attribute' => 'ur_addr_org',
                'label' => 'Юридический адрес организации',
                'format' => 'html',
                'value' => function($data) {
                    return $data->userSetting->ur_addr_org;
                }
            ],
            [
                'attribute' => 'ogrn',
                'label' => 'ОГРН',
                'format' => 'html',
                'value' => function($data) {
                    return $data->userSetting->ogrn;
                }
            ],
            [
                'attribute' => 'inn',
                'label' => 'ИНН',
                'format' => 'html',
                'value' => function($data) {
                    return $data->userSetting->inn;
                }
            ],
            [
                'attribute' => 'kpp',
                'label' => 'КПП',
                'format' => 'html',
                'value' => function($data) {
                    return $data->userSetting->kpp;
                }
            ],
            [
                'attribute' => 'bik_banka',
                'label' => 'БИК Банка',
                'format' => 'html',
                'value' => function($data) {
                    return $data->userSetting->bik_banka;
                }
            ],
            [
                'attribute' => 'name_bank',
                'label' => 'Название банка',
                'format' => 'html',
                'value' => function($data) {
                    return $data->userSetting->name_bank;
                }
            ],
            [
                'attribute' => 'kor_schet',
                'label' => 'Кор счет',
                'format' => 'html',
                'value' => function($data) {
                    return $data->userSetting->kor_schet;
                }
            ],
            [
                'attribute' => 'rass_schet',
                'label' => 'Рассчетный счет',
                'format' => 'html',
                'value' => function($data) {
                    return $data->userSetting->rass_schet;
                }
            ],
        ],
    ]); ?>


</div>
