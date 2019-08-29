<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%message_to_paid}}`.
 */
class m190827_140040_create_message_to_paid_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%message_to_paid}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "ID Бренда"',
            'service_type' => Schema::TYPE_TINYINT . '(1) NOT NULL COMMENT "Услуга"',
            'service_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "ID Услуги"',
            'date_to_paid' => Schema::TYPE_DATE . ' NOT NULL COMMENT "Дата оплаты"',
            'amount' => Schema::TYPE_DECIMAL . '(12,2) NOT NULL COMMENT "Сумма"',
            'debtor' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0 COMMENT "Должник"',
        ]);

        $this->addForeignKey('messageUserId', '{{%message_to_paid}}', 'user_id', '{{%user}}',
            'id', 'CASCADE');

        $this->addForeignKey('messageServiceId', '{{%message_to_paid}}', 'service_id', '{{%service}}',
            'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropForeignKey('messageUserId', '{{%message_to_paid}}');

        $this->dropTable('{{%message_to_paid}}');
    }
}
