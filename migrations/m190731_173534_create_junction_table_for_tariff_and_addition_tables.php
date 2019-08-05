<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tariff_addition}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tariff}}`
 * - `{{%addition}}`
 */
class m190731_173534_create_junction_table_for_tariff_and_addition_tables extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%tariff_addition}}', [
            'tariff_id' => $this->integer(),
            'addition_id' => $this->integer(),
            'PRIMARY KEY(tariff_id, addition_id)',
        ]);

        $this->createIndex(
            '{{%idx-tariff_addition-tariff_id}}',
            '{{%tariff_addition}}',
            'tariff_id'
        );

        $this->addForeignKey(
            '{{%fk-tariff_addition-tariff_id}}',
            '{{%tariff_addition}}',
            'tariff_id',
            '{{%tariff}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-tariff_addition-addition_id}}',
            '{{%tariff_addition}}',
            'addition_id'
        );

        $this->addForeignKey(
            '{{%fk-tariff_addition-addition_id}}',
            '{{%tariff_addition}}',
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
            '{{%fk-tariff_addition-tariff_id}}',
            '{{%tariff_addition}}'
        );

        $this->dropIndex(
            '{{%idx-tariff_addition-tariff_id}}',
            '{{%tariff_addition}}'
        );

        $this->dropForeignKey(
            '{{%fk-tariff_addition-addition_id}}',
            '{{%tariff_addition}}'
        );

        $this->dropIndex(
            '{{%idx-tariff_addition-addition_id}}',
            '{{%tariff_addition}}'
        );

        $this->dropTable('{{%tariff_addition}}');
    }
}
