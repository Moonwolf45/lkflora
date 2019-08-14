<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%tickets_text}}`.
 */
class m190813_152150_create_tickets_text_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%tickets_text}}', [
            'id' => Schema::TYPE_PK,
            'ticket_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "ID Обращения"',
            'text' => Schema::TYPE_TEXT . ' NOT NULL COMMENT "Текст"',
            'user_type' => Schema::TYPE_INTEGER . '(1) NOT NULL COMMENT "Тип пользователя"',
        ]);

        $this->addForeignKey('ticketsTextUserId', '{{%tickets_text}}', 'ticket_id', '{{%tickets}}',
            'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropForeignKey('ticketsTextUserId', '{{%tickets_text}}');

        $this->dropTable('{{%tickets_text}}');
    }
}
