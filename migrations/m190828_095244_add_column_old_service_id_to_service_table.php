<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles adding columns to table `{{%service}}`.
 */
class m190828_095244_add_column_old_service_id_to_service_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('{{%service}}', 'old_service_id', Schema::TYPE_INTEGER . ' NULL COMMENT "Старый ID Услуги"');
        $this->addColumn('{{%service}}', 'old_connection_date', Schema::TYPE_DATE . ' NULL COMMENT "Старая дата подключения"');
        $this->addColumn('{{%service}}', 'old_writeoff_date', Schema::TYPE_DATE . ' NULL COMMENT "Старая дата списания"');
        $this->addColumn('{{%service}}', 'old_writeoff_amount', Schema::TYPE_DECIMAL . '(12,2) NULL COMMENT "Старая сумма списания"');
        $this->addColumn('{{%service}}', 'edit_description', Schema::TYPE_STRING . ' NULL COMMENT "Описание изменения"');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('{{%service}}', 'old_writeoff_amount');
        $this->dropColumn('{{%service}}', 'old_writeoff_date');
        $this->dropColumn('{{%service}}', 'old_connection_date');
        $this->dropColumn('{{%service}}', 'old_service_id');
    }
}
