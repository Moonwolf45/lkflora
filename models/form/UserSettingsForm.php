<?php

namespace app\models\form;

use app\models\db\UserSettings;
use Yii;
use yii\base\Model;

/**
 * Форма сохранения данных на странице user/index
 * @package app\models\form
 */
class UserSettingsForm extends Model {

    public $type_org;
    public $name_org;
    public $ur_addr_org;
    public $ogrn;
    public $inn;
    public $kpp;
    public $bank_bic;
    public $bank_name;
    public $kor_schet;
    public $rass_schet;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ur_addr_org'], 'string'],
            [['type_org', 'name_org', 'ogrn', 'inn', 'kpp', 'bank_bic', 'bank_name', 'kor_schet', 'rass_schet'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id'          => 'ID',
            'user_id'     => 'ID пользователя',
            'doc_num'     => 'Номер договора',
            'type_org'    => 'Тип организации',
            'name_org'    => 'Название организации',
            'ur_addr_org' => 'Юридический адрес организации',
            'ogrn'        => 'ОГРН',
            'inn'         => 'ИНН',
            'kpp'         => 'КПП',
            'bik_banka'   => 'БИК Банка',
            'name_bank'   => 'Название банка',
            'kor_schet'   => 'Кор счет',
            'rass_schet'  => 'Рассчетный счет',
        ];
    }

    /**
     * Сохранение модели
     *
     * @return bool
     */
    public function save() {
        if (!$this->validate()) {
            return false;
        }

        $userId = Yii::$app->user->id;
        $userSettings = UserSettings::findOne(['user_id' => $userId]);

        if (!$userSettings) {
            $userSettings = new UserSettings;
        }

        $userSettings->user_id = $userId;
        $userSettings->type_org = $this->type_org;
        $userSettings->name_org = $this->name_org;
        $userSettings->ur_addr_org = $this->ur_addr_org;
        $userSettings->bik_banka = $this->bank_bic;
        $userSettings->name_bank = $this->bank_name;
        $userSettings->kpp = $this->kpp;
        $userSettings->inn = $this->inn;
        $userSettings->ogrn = $this->ogrn;
        $userSettings->kor_schet = $this->kor_schet;
        $userSettings->rass_schet = $this->rass_schet;

        return $userSettings->save();
    }

    /**
     *  Метод заполнения модели данными
     */
    public function loadData() {
        $userId = Yii::$app->user->id;
        $userSettings = UserSettings::findOne(['user_id' => $userId]);

        if ($userSettings){
            $this->type_org = $userSettings->type_org;
            $this->name_org = $userSettings->name_org;
            $this->ur_addr_org = $userSettings->ur_addr_org;
            $this->bank_bic = $userSettings->bik_banka;
            $this->bank_name = $userSettings->name_bank;
            $this->kpp = $userSettings->kpp;
            $this->inn = $userSettings->inn;
            $this->ogrn = $userSettings->ogrn;
            $this->kor_schet = $userSettings->kor_schet;
            $this->rass_schet = $userSettings->rass_schet;
        }
    }

}
