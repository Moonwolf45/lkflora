<?php

use yii\db\Migration;
use yii\db\Schema;

class m180819_150916_create_user_settings_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%user_settings}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "ID пользователя"',
            'doc_num' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "Номер договора"',
            'type_org' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Тип организации"',
            'name_org' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Название организации"',
            'ur_addr_org' => Schema::TYPE_TEXT . ' NOT NULL COMMENT "Юр. адрес организации"',
            'ogrn' => Schema::TYPE_STRING . ' NOT NULL COMMENT "ОГРН"',
            'inn' => Schema::TYPE_STRING . ' NOT NULL COMMENT "ИНН"',
            'kpp' => Schema::TYPE_STRING . ' NOT NULL COMMENT "КПП"',
            'bik_banka' => Schema::TYPE_STRING . ' NOT NULL COMMENT "БИК Банка"',
            'name_bank' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Название банка"',
            'kor_schet' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Кор. счет"',
            'rass_schet' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Рассчетный счет"',
        ]);

        $this->addForeignKey('userSettingsUserId', '{{%user_settings}}', 'user_id', '{{%user}}',
            'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropForeignKey('userSettingsUserId', '{{%user_settings}}');

        $this->dropTable('{{%user_settings}}');
    }

}
