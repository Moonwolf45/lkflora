<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles adding column to table `{{%tariff}}`.
 */
class m190731_173812_add_column_to_tariff_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('{{%tariff}}', 'status_con', Schema::TYPE_INTEGER . ' COMMENT "Статус"');
        $this->addColumn('{{%tariff}}', 'default_con', Schema::TYPE_INTEGER . ' COMMENT "Состояние сввязи"');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('{{%tariff}}', 'status_con');
        $this->dropColumn('{{%tariff}}', 'default_con');
    }
}
