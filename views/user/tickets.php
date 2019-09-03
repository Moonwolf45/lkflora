<?php

use app\models\tickets\TicketsFiles;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/** @var $newTicket app\models\tickets\Tickets */
/** @var $newTicketText app\models\tickets\TicketsText */

$this->title = 'Техподдержка';

$request = Yii::$app->request;
$id = $request->get('id');

$ticket = 'none';
$form_ticket = 'block';

if ($id != '') {
    $ticket = 'block';
    $form_ticket = 'none';
}

?>

<div class="content">
    <h2 class="content__title">Сообщения</h2>
    <div class="content__row">
        <div class="content__col-9 content__col-9_messages">
            <!-- СОЗДАНИЕ ОБРАЩЕНИЯ В ТЕХПОДДЕРЖКУ -->
            <div class="content__box content__box_appeal js-content__box_appeal" style="display:<?= $form_ticket; ?>;">
                <div class="appeal">
                    <div class="appeal__wrapp appeal__wrapp_mw635 appeal__wrapp_p2">
                        <div class="back-btn">назад</div>
                        <div class="little-title">Создание обращения в техподдержку</div>
                        <div class="declaration">
                            <p class="declaration__text">На бесплатном тарифе Техподдержка работает с пн-пт с 10.00 до 19.00</p>
                            <a href="#" class="declaration__link">перейти на платный тариф</a>
                        </div>
                        <?php $form5 = ActiveForm::begin([
                            'options' => ['class' => 'appeal__form', 'data' => ['pjax' => true],
                                'enctype' => 'multipart/form-data']
                        ]); ?>
                            <div class="appeal__block">
                                <?= $form5->field($newTicket, 'user_id')->hiddenInput(['value' => Yii::$app->user->id])
                                    ->label(false); ?>
                                <div class="field">
                                    <p class="field__text">тема обращения</p>
                                </div>

                                <?= $form5->field($newTicket, 'subject')->textInput([
                                    'class' => 'input choose__name choose__name-appeal',
                                    'placeholder' => 'Введите тему'])->label(false); ?>

                                <?= $form5->field($newTicket, 'tickets_text')->textarea([
                                    'class' => 'textarea textarea_mb20', 'placeholder' => 'Введите текст сообщения', 'cols' => 30,
                                    'rows' => 20])->label(false); ?>

                                <div class="attach">
                                    <div class="attach__wrapp-label">
                                        <label class="attach__label" id="label-file1" for="file1">
                                            <?= Html::activeFileInput($newTicket, 'ticketFiles[]', [
                                                'multiple' => true, 'class' => 'left clip-input attach__input',
                                                'id' => 'file1', 'accept' => 'image/jpeg, image/pjpeg, image/jpeg, 
                                                image/jpeg, image/pjpeg, image/jpeg, image/pjpeg, image/jpeg, 
                                                image/pjpeg, image/png, application/pdf, application/msword,
                                                application/excel, application/vnd.ms-excel, application/x-excel,
                                                application/x-msexcel, 
                                                application/vnd.openxmlformats-officedocument.wordprocessingml.document,
                                                application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']); ?>
                                            <span class="attach__icon s-di-vertical-m"></span>
                                            <span class="attach__text s-di-vertical-m clip-input-txt">Прикрепить файл</span>
                                        </label>
                                        <?= Html::error($newTicket, 'ticketFiles[]', ['class' => 'help-block']); ?>
                                    </div>
                                </div>
                            </div>

                            <?= Html::submitButton('Отправить', ['class' => 'button button_width-200px appeal__button']); ?>
                            <?= Html::resetButton('Назад', ['class' => 'button appeal__button button_gray js-add-something-support']); ?>
                        <?php $form5 = ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
            <!-- СОЗДАНИЕ ОБРАЩЕНИЯ В ТЕХПОДДЕРЖКУ -->

            <div class="content__box content__box_appeal ticket_box" style="display:<?= $ticket; ?>;">
                <div class="discussion">
                    <div class="back-btn back-btn_message">Назад</div>
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

                                                    <p class="discussion__name"><?= $openTicket['user']['company_name']; ?></p>

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
                                                    'id' => 'file1', 'accept' => 'image/jpeg, image/pjpeg, image/jpeg,
                                                    image/jpeg, image/pjpeg, image/jpeg, image/pjpeg, image/jpeg,
                                                    image/pjpeg, image/png, application/pdf, application/msword,
                                                    application/excel, application/vnd.ms-excel, application/x-excel,
                                                    application/x-msexcel,
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

        <div class="content__col-3 content__col-3-messages">
            <div class="content__box content__box-messages">
                <div class="support__wrapp s-pt0">
                    <div class="support__box_mobile">
                        <p class="sub-title sub-title_desktop">
                            <span class="sub-title__span-desktop">Обращения</span>
                            <span class="sub-title__span">Создание обращения в техподдержку</span>
                        </p>
                        <div class="declaration declaration_support">
                            <p class="declaration__text">На бесплатном тарифе Техподдержка работает с пн-пт с 10.00 до 19.00</p>
                            <a href="#" class="declaration__link">перейти на платный тариф</a>
                        </div>
                    </div>
                    <div class="add-something add-something-support js-add-something-support">
                        <p class="sub-title">Обращения</p>
                        <div class="plus plus_desktop"></div>
                        <div class="add-something__box add-something__box_mobile">
                            <div class="plus"></div>
                            <p class="sub-title sub-title_no-ttu ">Новое обращение</p>
                        </div>
                    </div>
                    <div class="support__block">
                        <?php if (!empty($tickets)): ?>
                            <?php foreach ($tickets as $ticket): ?>
                                <a class="support__box" data-pjax="0" href="<?= Url::to(['/user/tickets', 'id' => $ticket['id']]); ?>">
                                    <?php if ($ticket['new_text']) {
                                        $dop_class = 'support__box-title_circle';
                                    } ?>
                                    <p class="support__box-title <?php echo $dop_class; ?>">
                                        <?= $ticket['subject']; ?>
                                    </p>
                                    <p class="support__box-text">
                                        <?= $ticket['lastTicket']['text']; ?>
                                    </p>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
