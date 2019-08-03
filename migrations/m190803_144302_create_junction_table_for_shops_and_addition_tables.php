<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shops_addition}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%shops}}`
 * - `{{%addition}}`
 */
class m190803_144302_create_junction_table_for_shops_and_addition_tables extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%shops_addition}}', [
            'shop_id' => $this->integer(),
            'addition_id' => $this->integer(),
            'PRIMARY KEY(shop_id, addition_id)',
        ]);

        $this->createIndex(
            '{{%idx-shops_addition-shop_id}}',
            '{{%shops_addition}}',
            'shop_id'
        );

        $this->addForeignKey(
            '{{%fk-shops_addition-shop_id}}',
            '{{%shops_addition}}',
            'shop_id',
            '{{%shops}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-shops_addition-addition_id}}',
            '{{%shops_addition}}',
            'addition_id'
        );

        $this->addForeignKey(
            '{{%fk-shops_addition-addition_id}}',
            '{{%shops_addition}}',
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
            '{{%fk-shops_addition-shop_id}}',
            '{{%shops_addition}}'
        );

        $this->dropIndex(
            '{{%idx-shops_addition-shop_id}}',
            '{{%shops_addition}}'
        );

        $this->dropForeignKey(
            '{{%fk-shops_addition-addition_id}}',
            '{{%shops_addition}}'
        );

        $this->dropIndex(
            '{{%idx-shops_addition-addition_id}}',
            '{{%shops_addition}}'
        );

        $this->dropTable('{{%shops_addition}}');
    }
}
