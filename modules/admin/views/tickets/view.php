<?php

use app\models\tickets\TicketsFiles;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\tickets\Tickets */

$this->title = $model->id . ' - ' . $model->user->company_name . ' - ' . $model->subject;
$this->params['breadcrumbs'][] = ['label' => 'Тикеты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this); ?>

<div class="addition-view">
    <h1><?= Html::encode($this->title); ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], ['class' => 'btn btn-danger',
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
            'subject',
            [
                'attribute' => 'new_text',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->new_text) {
                        return '<p class="text-danger">Нет</p>';
                    } else {
                        return '<p class="text-success">Да</p>';
                    }
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->status) {
                        return '<p class="text-success">Открыто</p>';
                    } else {
                        return '<p class="text-danger">Закрыто</p>';
                    }
                }
            ],
        ],
    ]); ?>

    <div class="discussion">
        <div class="discussion__content">
            <ul class="discussion__list">
                <?php if (!empty($tickets_text)): ?>
                    <?php foreach ($tickets_text as $textTicket): ?>
                        <?php if ($textTicket['user_type'] == 0): ?>
                            <li class="discussion__item discussion__item_right">
                                <div class="discussion__data-message">
                                    <?php if (!empty($textTicket['ticketsFiles'])): ?>
                                        <div class="discussion__uploaded-photos">
                                            <?php $countFile = count($textTicket['ticketsFiles']);
                                            if ($countFile == 1) {
                                                $class = "discussion__col-12";
                                            } elseif ($countFile == 2) {
                                                $class = "discussion__col-6";
                                            } elseif ($countFile == 3) {
                                                $class = "discussion__col-4";
                                            } else {
                                                $class = "discussion__col-3";
                                            } ?>

                                            <?php foreach ($textTicket['ticketsFiles'] as $file): ?>
                                                <div class="<?= $class; ?>">
                                                    <?php if ($file['type_file'] == TicketsFiles::TYPE_IMAGE): ?>
                                                        <div class="discussion__uploaded-photo">
                                                            <a data-fancybox="gallery" href="<?= Url::to('@web/' . $file['file']); ?>">
                                                                <?= Html::img('@web/' . $file['file']); ?>
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <p class="discussion__name"><?= Yii::$app->user->identity->company_name; ?></p>

                                    <?php if (!empty($textTicket['ticketsFiles'])): ?>
                                        <?php foreach ($textTicket['ticketsFiles'] as $file): ?>
                                            <?php if ($file['type_file'] == TicketsFiles::TYPE_FILE): ?>
                                                <a href="<?= Url::to('@web/' . $file['file']); ?>" download>
                                                    <?= $file['name_file']; ?>
                                                </a>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <div class="discussion__message" >
                                        <div class="discussion__sms" data-time-sms="<?= Yii::$app->formatter->asDatetime($textTicket['date_time']); ?>">
                                            <p><?= $textTicket['text']; ?></p>
                                        </div>
                                        <div class="discussion__avatar">
                                            <?php if (Yii::$app->user->identity->avatar != ''): ?>
                                                <?= Html::img('@web/' . Yii::$app->user->identity->avatar, ['class' => 'discussion__img']); ?>
                                            <?php else: ?>
                                                <?= Html::img('@web/images/user-photo.png', ['class' => 'discussion__img']); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php else: ?>
                            <li class="discussion__item discussion__item_left">
                                <div class="discussion__data-message">
                                    <?php if (!empty($textTicket['ticketsFiles'])): ?>
                                        <div class="discussion__uploaded-photos">
                                            <?php $countFile = count($textTicket['ticketsFiles']);
                                            if ($countFile == 1) {
                                                $class = "discussion__col-12";
                                            } elseif ($countFile == 2) {
                                                $class = "discussion__col-6";
                                            } elseif ($countFile == 3) {
                                                $class = "discussion__col-4";
                                            } else {
                                                $class = "discussion__col-3";
                                            } ?>

                                            <?php foreach ($textTicket['ticketsFiles'] as $file): ?>
                                                <div class="<?= $class; ?>">
                                                    <?php if ($file['type_file'] == TicketsFiles::TYPE_IMAGE): ?>
                                                        <div class="discussion__uploaded-photo">
                                                            <a data-fancybox="gallery" href="<?= Url::to('@web/' . $file['file']); ?>">
                                                                <?= Html::img('@web/' . $file['file']); ?>
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <p class="discussion__name">Техподдержка</p>
                                    <?php if (!empty($textTicket['ticketsFiles'])): ?>
                                        <?php foreach ($textTicket['ticketsFiles'] as $file): ?>
                                            <?php if ($file['type_file'] == TicketsFiles::TYPE_FILE): ?>
                                                <a href="<?= Url::to('@web/' . $file['file']); ?>" download>
                                                    <?= $file['name_file']; ?>
                                                </a>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <div class="discussion__message" >
                                        <div class="discussion__sms" data-time-sms="<?= Yii::$app->formatter->asDatetime($textTicket['date_time']); ?>">
                                            <p><?= $textTicket['text']; ?></p>
                                        </div>
                                        <div class="discussion__avatar">
                                            <?= Html::img('@web/images/icon/icon_QA.png', ['class' => 'discussion__img']); ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
