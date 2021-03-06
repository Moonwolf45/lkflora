<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%tickets_files}}`.
 */
class m190813_152601_create_tickets_files_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%tickets_files}}', [
            'id' => Schema::TYPE_PK,
            'ticket_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "ID Обращения"',
            'ticket_text_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "ID Обращения пользователя"',
            'type_file' => Schema::TYPE_TINYINT . '(1) DEFAULT 0 NOT NULL COMMENT "Тип файла"',
            'file' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Картинка"',
            'name_file' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Имя файла"',
        ]);

        $this->addForeignKey('ticketsFilesUserId', '{{%tickets_files}}', 'ticket_id', '{{%tickets}}',
            'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropForeignKey('ticketsFilesUserId', '{{%tickets_files}}');

        $this->dropTable('{{%tickets_files}}');
    }
}
