<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%tickets}}`.
 */
class m190813_152133_create_tickets_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%tickets}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "ID Бренда"',
            'subject' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Тема"',
            'status' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT 1 COMMENT "Открыто\закрыто обращение"',
            'new_text' => Schema::TYPE_TINYINT . '(1) NOT NULL COMMENT "Есть новое сообщение"',
        ]);

        $this->addForeignKey('ticketsUserId', '{{%tickets}}', 'user_id', '{{%user}}',
            'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropForeignKey('ticketsUserId', '{{%tickets}}');

        $this->dropTable('{{%tickets}}');
    }
}
