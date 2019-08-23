<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%serevice}}`.
 */
class m190808_112601_create_service_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%service}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "ID Бренда"',
            'shop_id' => Schema::TYPE_INTEGER . ' COMMENT "ID Магазина"',
            'type_service' => Schema::TYPE_INTEGER . '(1) NOT NULL COMMENT "Тип услуги"',
            'type_serviceId' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "ID Услуги на которую планируется списание"',
            'writeoff_date' => Schema::TYPE_DATE . ' NOT NULL COMMENT "Дата списания"',
            'writeoff_amount' => Schema::TYPE_DECIMAL . '(12,2) NOT NULL COMMENT "Сумма списания"',
            'quantity' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1 COMMENT "Количество"',
            'agree' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0 COMMENT "Подтверждение"',
            'repeat' => Schema::TYPE_INTEGER . '(1) NOT NULL DEFAULT 0 COMMENT "Повторяющийся"',
            'completed' => Schema::TYPE_INTEGER . '(1) NOT NULL DEFAULT 0 COMMENT "Выполнен"',
        ]);

        $this->addForeignKey('serviceUserId', '{{%service}}', 'user_id', '{{%user}}',
            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropForeignKey('serviceUserId', '{{%service}}');

        $this->dropTable('{{%service}}');
    }
}
