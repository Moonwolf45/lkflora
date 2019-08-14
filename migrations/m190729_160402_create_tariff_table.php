<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m190729_160402_create__tariffTable
 */
class m190729_160402_create_tariff_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%tariff}}', [
            'id' => Schema::TYPE_PK,
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'cost' => Schema::TYPE_DECIMAL . '(12,2) NOT NULL COMMENT "Стоимость обслуживания (ежемесячно)"',
            'about' => Schema::TYPE_TEXT . ' COMMENT "Описание"',
            'drop' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0 COMMENT "Параметр который запрещает подключать тариф ниже данного"',
            'status' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 1 COMMENT "Статус"',
            'term' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "Срок действия тарифа, после которого он не может быть повторно подключен"'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%tariff}}');
    }

}
