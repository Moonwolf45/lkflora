<?php

namespace app\models\db;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_settings".
 *
 * @property int    $id
 * @property int    $user_id     ID пользователя
 * @property string $doc_num     Номер договора
 * @property string $type_org    Тип организации
 * @property string $name_org    Название организации
 * @property string $ur_addr_org Юр адрес организации
 * @property string $ogrn        ОГРН
 * @property string $inn         ИНН
 * @property string $kpp         КПП
 * @property string $bik_banka   БИК Банка
 * @property string $name_bank   Название банка
 * @property string $kor_schet   Кор счет
 * @property string $rass_schet  Рассчетный счет
 */
class UserSettings extends ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'user_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['ur_addr_org'], 'string'],
            [['doc_num', 'type_org', 'name_org', 'ogrn', 'inn', 'kpp', 'bik_banka', 'name_bank', 'kor_schet', 'rass_schet'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
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
}
