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
            'address' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Адрес магазина"',
            'tariff_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "Привязка к тарифу"',
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "Привязка к бренду"',
            'deleted' => Schema::TYPE_INTEGER . '(1) NOT NULL DEFAULT 0 COMMENT "Привязка к бренду"',
        ]);

        $this->addForeignKey('shopsUserId', '{{%shops}}', 'user_id', '{{%user}}',
            'id', 'CASCADE');

        $this->addForeignKey('shopsTariffId', '{{%shops}}', 'tariff_id', '{{%tariff}}',
            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropForeignKey('shopsTariffId', '{{%shops}}');
        $this->dropForeignKey('shopsUserId', '{{%shops}}');

        $this->dropTable('{{%shops}}');
    }
}
