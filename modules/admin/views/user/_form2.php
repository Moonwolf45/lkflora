<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @return string
 * @var $model app\models\db\User
 * @var $form  yii\widgets\ActiveForm
 * @var $this  yii\web\View
 */

?>

<div class="user-form">
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'email')->textInput(['class' => 'form-control']); ?>

        <?= $form->field($model, 'phone')->widget(MaskedInput::class, [
            'mask' => '+7 (999) 999-99-99',
        ]); ?>

        <?= $form->field($model, 'doc_num')->textInput(['value' => $model->userSetting->doc_num])
            ->label('Номер договора'); ?>

<!--        --><?//= $form->field($model, 'type_org')->dropDownList(['ip' => 'ИП', 'ooo' => 'ООО/АО/ЗАО'],
//            ['prompt' => 'Выберите тип организации'], ['options' => [$model->userSetting->type_org => [
//                'selected' => true]]])->label('Тип организации'); ?>

        <?= $form->field($model, 'name_org')->textInput(['value' => $model->userSetting->name_org])
            ->label('Название организации'); ?>

        <?= $form->field($model, 'ur_addr_org')->textInput(['value' => $model->userSetting->ur_addr_org])
            ->label('Юридический адрес организации'); ?>

        <?= $form->field($model, 'ogrn')->textInput(['value' => $model->userSetting->ogrn])
            ->label('ОГРН'); ?>

        <?= $form->field($model, 'inn')->textInput(['value' => $model->userSetting->inn])
            ->label('ИНН'); ?>

        <?= $form->field($model, 'kpp')->textInput(['value' => $model->userSetting->kpp])
            ->label('КПП'); ?>

        <?= $form->field($model, 'bik_banka')->textInput(['value' => $model->userSetting->bik_banka])
            ->label('БИК Банка'); ?>

        <?= $form->field($model, 'name_bank')->textInput(['value' => $model->userSetting->name_bank])
            ->label('Название банка'); ?>

        <?= $form->field($model, 'kor_schet')->textInput(['value' => $model->userSetting->kor_schet])
            ->label('Кор счет'); ?>

        <?= $form->field($model, 'rass_schet')->textInput(['value' => $model->userSetting->rass_schet])
            ->label('Рассчетный счет'); ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
