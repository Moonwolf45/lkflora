<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m190830_155039_add_index_to_db
 */
class m190830_155039_add_index_to_db extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createIndex('index-user-id', '{{%user}}', 'id');
        $this->createIndex('index-user_settings-user_id', '{{%user_settings}}', 'user_id');
        $this->createIndex('index-tariff-id', '{{%tariff}}', 'id');
        $this->createIndex('index-shops-id', '{{%shops}}', 'id');
        $this->createIndex('index-addition-id', '{{%addition}}', 'id');
        $this->createIndex('index-service-id', '{{%service}}', 'id');
        $this->createIndex('index-payments-id', '{{%payments}}', 'id');
        $this->createIndex('index-tickets-id', '{{%tickets}}', 'id');
        $this->createIndex('index-tickets_files-id', '{{%tickets_files}}', 'id');
        $this->createIndex('index-tickets_files-ticket_text_id', '{{%tickets_files}}', 'ticket_text_id');
        $this->createIndex('index-tickets_text-id', '{{%tickets_text}}', 'id');
        $this->createIndex('index-transaction-id', '{{%transaction}}', 'user_id');
        $this->createIndex('index-message_to_paid-id', '{{%message_to_paid}}', 'id');

        $this->addColumn('{{%tariff_addition}}', 'quantity', Schema::TYPE_INTEGER . ' NULL COMMENT "Количество которое можно подключить в данном тарифе"');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(){
        $this->dropColumn('{{%tariff_addition}}', 'quantity');

        $this->dropIndex('index-message_to_paid-id', '{{%message_to_paid}}');
        $this->dropIndex('index-transaction-id', '{{%transaction}}');
        $this->dropIndex('index-tickets_text-id', '{{%tickets_text}}');
        $this->dropIndex('index-tickets_files-ticket_text_id', '{{%tickets_files}}');
        $this->dropIndex('index-tickets_files-id', '{{%tickets_files}}');
        $this->dropIndex('index-tickets-id', '{{%tickets}}');
        $this->dropIndex('index-payments-id', '{{%payments}}');
        $this->dropIndex('index-service-id', '{{%service}}');
        $this->dropIndex('index-addition-id', '{{%addition}}');
        $this->dropIndex('index-shops-id', '{{%shops}}');
        $this->dropIndex('index-tariff-id', '{{%tariff}}');
        $this->dropIndex('index-user_settings-user_id', '{{%user_settings}}');
        $this->dropIndex('index-user-id', '{{%user}}');

        return false;
    }

}
