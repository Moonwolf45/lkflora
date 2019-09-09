<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%service}}`.
 */
class m190808_112601_create_service_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%service}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "ID Бренда"',
            'shop_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "ID Магазина"',
            'type_service' => Schema::TYPE_TINYINT . '(1) NOT NULL COMMENT "Тип услуги"',
            'type_serviceId' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "ID Услуги на которую планируется списание"',
            'connection_date' => Schema::TYPE_DATE . ' NOT NULL COMMENT "Дата подключения"',
            'writeoff_date' => Schema::TYPE_DATE . ' NOT NULL COMMENT "Дата списания"',
            'writeoff_amount' => Schema::TYPE_DECIMAL . '(12,2) NOT NULL COMMENT "Сумма списания"',
            'agree' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0 COMMENT "Подтверждение"',
            'repeat_service' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT 0 COMMENT "Повторяющийся"',
            'deleted' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT 0 COMMENT "Удалён"',
        ]);

        $this->addForeignKey('serviceUserId', '{{%service}}', 'user_id', '{{%user}}',
            'id');

        $this->addForeignKey('serviceShopsId', '{{%service}}', 'shop_id', '{{%shops}}',
            'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropForeignKey('serviceUserId', '{{%service}}');
        $this->dropForeignKey('serviceShopsId', '{{%service}}');

        $this->dropTable('{{%service}}');
    }
}
