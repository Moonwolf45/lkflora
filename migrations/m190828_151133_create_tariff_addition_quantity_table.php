<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%tariff_addition_quantity}}`.
 */
class m190828_151133_create_tariff_addition_quantity_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->dropColumn('{{%tariff}}', 'status_con');
        $this->dropColumn('{{%tariff}}', 'default_con');

        $this->createTable('{{%tariff_addition_quantity}}', [
            'tariff_id' => Schema::TYPE_INTEGER,
            'addition_id' => Schema::TYPE_INTEGER,
            'status_con' => Schema::TYPE_INTEGER . ' NULL COMMENT "Количество которое можно подключить в данном тарифе"',
            'PRIMARY KEY(tariff_id, addition_id)',
        ]);

        $this->createIndex(
            '{{%idx-tariff_addition_quantity-tariff_id}}',
            '{{%tariff_addition_quantity}}',
            'tariff_id'
        );

        $this->addForeignKey(
            '{{%fk-tariff_addition_quantity-tariff_id}}',
            '{{%tariff_addition_quantity}}',
            'tariff_id',
            '{{%tariff}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-tariff_addition_quantity-addition_id}}',
            '{{%tariff_addition_quantity}}',
            'addition_id'
        );

        $this->addForeignKey(
            '{{%fk-tariff_addition_quantity-addition_id}}',
            '{{%tariff_addition_quantity}}',
            'addition_id',
            '{{%addition}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropForeignKey(
            '{{%fk-tariff_addition_quantity-addition_id}}',
            '{{%tariff_addition_quantity}}'
        );

        $this->dropIndex(
            '{{%idx-tariff_addition_quantity-addition_id}}',
            '{{%tariff_addition_quantity}}'
        );

        $this->dropForeignKey(
            '{{%fk-tariff_addition_quantity-tariff_id}}',
            '{{%tariff_addition_quantity}}'
        );

        $this->dropIndex(
            '{{%idx-tariff_addition_quantity-tariff_id}}',
            '{{%tariff_addition_quantity}}'
        );

        $this->dropTable('{{%tariff_addition_quantity}}');
    }
}
