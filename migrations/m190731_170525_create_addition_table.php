<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%addition}}`.
 */
class m190731_170525_create_addition_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%addition}}', [
            'id' => Schema::TYPE_PK,
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Название"',
            'cost' => Schema::TYPE_DECIMAL . '(12,2) NOT NULL COMMENT "Стоимость"',
            'about' => Schema::TYPE_STRING . ' COMMENT "Описание"',
            'type' => Schema::TYPE_INTEGER . '(3) NOT NULL COMMENT "Тип"',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%addition}}');
    }
}
