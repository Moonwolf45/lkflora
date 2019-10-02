<?php

use app\models\tickets\TicketsFiles;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $openTicket app\models\tickets\Tickets */
/* @var $newTicketText app\models\tickets\TicketsText */

$this->title = 'Тикет: ' . $openTicket['subject'];
$this->params['breadcrumbs'][] = ['label' => 'Тикеты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $openTicket['subject'], 'url' => ['view', 'id' => $openTicket['id']]];
$this->params['breadcrumbs'][] = 'Переписка';
?>

<div class="tickets-update">
    <h1><?= Html::encode($this->title); ?></h1>

    <div class="content__box content__box_appeal ticket_box">
        <div class="discussion">
            <?php Pjax::begin(); ?>
            <div class="discussion__content">
                <ul class="discussion__list">
                    <?php if (!empty($openTicket)): ?>
                        <?php foreach($openTicket['ticketsText'] as $textTicket): ?>
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

                                                <?php foreach($textTicket['ticketsFiles'] as $file): ?>
                                                    <div class="<?= $class; ?>">
                                                        <?php if($file['type_file'] == TicketsFiles::TYPE_IMAGE): ?>
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
                                            <?php foreach($textTicket['ticketsFiles'] as $file): ?>
                                                <?php if($file['type_file'] == TicketsFiles::TYPE_FILE): ?>
                                                    <a href="<?= Url::to('@web/' . $file['file']); ?>" data-pjax="0" download>
                                                        <?= $file['name_file']; ?>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <div class="discussion__message" >
                                            <div class="discussion__sms" data-time-sms="<?= Yii::$app->
                                            formatter->asDatetime($textTicket['date_time']); ?>">
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

                                                <?php foreach($textTicket['ticketsFiles'] as $file): ?>
                                                    <div class="<?= $class; ?>">
                                                        <?php if($file['type_file'] == TicketsFiles::TYPE_IMAGE): ?>
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
                                            <?php foreach($textTicket['ticketsFiles'] as $file): ?>
                                                <?php if($file['type_file'] == TicketsFiles::TYPE_FILE): ?>
                                                    <a href="<?= Url::to('@web/' . $file['file']); ?>" data-pjax="0" download>
                                                        <?= $file['name_file']; ?>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <div class="discussion__message" >
                                            <div class="discussion__sms" data-time-sms="<?= Yii::$app->
                                            formatter->asDatetime($textTicket['date_time']); ?>">
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
            <?php Pjax::end(); ?>
        </div>

        <div class="appeal">
            <div class="appeal__wrapp appeal__wrapp_pt30 appeal__wrapp_mw655">
                <?php if (!empty($openTicket)): ?>
                    <?php $form2 = ActiveForm::begin(['options' => ['class' => 'appeal__form appeal__form_massages',
                        'data' => ['pjax' => true], 'enctype' => 'multipart/form-data']]); ?>
                    <div class="appeal__block appeal__block_massages">
                        <?= $form2->field($newTicketText, 'user_id')->hiddenInput([
                            'value' => Yii::$app->user->id])->label(false); ?>

                        <?= $form2->field($newTicketText, 'ticket_id')->hiddenInput([
                            'value' => $openTicket['id']])->label(false); ?>

                        <?= $form2->field($newTicketText, 'text')->textarea([
                            'class' => 'textarea textarea_massages textarea_mb20',
                            'placeholder' => 'Введите текст сообщения', 'cols' => 30, 'rows' => 20])
                            ->label(false); ?>

                        <div class="attach attach_messages">
                            <div class="attach__wrapp-label attach__wrapp-label_messages">
                                <label class="attach__label" id="label-file1" for="file1">
                                    <?= Html::activeFileInput($newTicketText, 'ticketsFiles[]', [
                                        'multiple' => true, 'class' => 'left clip-input1 attach__input',
                                        'id' => 'file1', 'accept' => 'image/jpeg, image/pjpeg, image/png, 
                                                    application/pdf, application/msword, application/excel, 
                                                    application/vnd.ms-excel, application/x-excel, application/x-msexcel,
                                                    application/vnd.openxmlformats-officedocument.wordprocessingml.document,
                                                    application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']); ?>
                                    <span class="attach__icon s-di-vertical-m"></span>
                                    <span class="attach__text s-di-vertical-m clip-input-txt1">Прикрепить файл</span>
                                </label>
                                <?= Html::error($newTicketText, 'ticketsFiles[]', ['class' => 'help-block']); ?>
                            </div>
                        </div>
                        <?= Html::submitButton('Отправить', ['class' => 'button button_small button_width-200px appeal__button']); ?>
                    </div>
                    <?php $form2 = ActiveForm::end(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php
$script = <<< JS
    // Attach file
    $(".clip-input1").change(function () {
        const input = $(this)[0];
        let filename = '';
        let d = 0;
        
        for (let i = 0; i < input.files.length; i++) {
            d = i;
            d++;
            
            if (d === input.files.length) {
                filename += input.files[i].name;
            } else {
                filename += input.files[i].name + ', ';
            }
        }
        
        $(".clip-input-txt1").text(filename);
    });
JS;

$this->registerJs($script, View::POS_READY); ?>
