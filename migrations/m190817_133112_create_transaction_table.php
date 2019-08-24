<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%transaction}}`.
 */
class m190817_133112_create_transaction_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%transaction}}', [
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "ID Бренда"',
            'transaction_id' => Schema::TYPE_STRING . ' NOT NULL COMMENT "ID Транзации"',
            'status' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT 0 COMMENT "Статус"',
            'PRIMARY KEY(user_id)'
        ]);

        $this->addForeignKey('transactionUserId', '{{%transaction}}', 'user_id', '{{%user}}',
            'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropForeignKey('transactionUserId', '{{%transaction}}');

        $this->dropTable('{{%transaction}}');
    }
}
