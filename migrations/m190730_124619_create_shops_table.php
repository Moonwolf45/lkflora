<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%shops}}`.
 */
class m190730_124619_create_shops_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%shops}}', [
            'id' => Schema::TYPE_PK,
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Название магазина"',
            'address' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Адрес магазина"',
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "Привязка к пользователю"',
        ]);

        $this->addForeignKey(
            'shopsUserId',  // это "условное имя" ключа
            '{{%shops}}', // это название текущей таблицы
            'user_id', // это имя поля в текущей таблице, которое будет ключом
            '{{%user}}', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%shops}}');

        $this->dropForeignKey(
            'shopsUserId',
            '{{%user}}'
        );
    }
}
