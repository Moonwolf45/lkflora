<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/** @var TYPE_NAME $newTicket */
/** @var TYPE_NAME $newTicketText */

$this->title = 'Техподдержка';

?>

<div class="content">
    <h2 class="content__title">Сообщения</h2>
    <div class="content__row">
        <div class="content__col-9 content__col-9_messages">
            <div class="content__box">
                <div class="discussion">
                    <div class="discussion__content">
                        <?php Pjax::begin(); ?>
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
                                                                    <div class="discussion__uploaded-photo">
                                                                        <?= Html::img('@web/' . $file['file'], ['class' => 'discussion__img']); ?>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <p class="discussion__name"><?= $openTicket['user']['company_name']; ?></p>
                                                    <div class="discussion__message" >
                                                        <div class="discussion__sms" data-time-sms="<?= Yii::$app->
                                                            formatter->asDatetime($textTicket['date_time']); ?>">
                                                            <p><?= $textTicket['text']; ?></p>
                                                        </div>
                                                        <div class="discussion__avatar">
                                                            <?php if ($openTicket['user']['avatar'] != ''): ?>
                                                                <?= Html::img('@web/' . $openTicket['user']['avatar'], ['class' => 'discussion__img']); ?>
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
                                                                    <div class="discussion__uploaded-photo">
                                                                        <?= Html::img('@web/' . $file['file'], ['class' => 'discussion__img']); ?>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <p class="discussion__name">Техподдержка</p>
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
                        <?php Pjax::end();?>
                    </div>

                </div>
                <div class="appeal">
                    <div class="appeal__wrapp appeal__wrapp_pt30 appeal__wrapp_mw655 appeal__wrapp_mb30">
                        <?php if (!empty($openTicket)): ?>
                            <?php $form2 = ActiveForm::begin(['options' => ['class' => 'appeal__form',
                                'data' => ['pjax' => true], 'enctype' => 'multipart/form-data']]); ?>
                                <div class="appeal__block">
                                    <?= $form2->field($newTicketText, 'user_id')->hiddenInput(['value' => Yii::$app->user->id])
                                        ->label(false); ?>

                                    <?= $form2->field($newTicketText, 'ticket_id')->hiddenInput(['value' => $openTicket['id']])
                                        ->label(false); ?>

                                    <?= $form2->field($newTicketText, 'text')->textarea([
                                        'class' => 'textarea textarea_mb20', 'placeholder' => 'Введите текст сообщения', 'cols' => 30,
                                        'rows' => 20])->label(false); ?>

                                    <div class="attach">
                                        <div class="attach__wrapp-label">
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
                                </div>
                                <?= Html::submitButton('Отправить', ['class' => 'button button_width-200px appeal__button']); ?>
                            <?php $form2 = ActiveForm::end(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="content__col-3 content__col-3-messages">
            <div class="content__box content__box-messages">
                <div class="support">
                    <div class="support__wrapp">
                        <div class="add-something add-something-support d-f-between">
                            <button class="add-something__text add-something__text_fs14 add-something__text-support" data-jsx-modal-target="appeal">
                                Создать обращение
                            </button>
                            <div class="add-something__plus"></div>
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
</div>

<?php echo $this->render('modal/appeal', compact('newTicket')); ?>
