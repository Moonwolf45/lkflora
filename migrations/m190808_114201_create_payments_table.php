<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%payments}}`.
 */
class m190808_114201_create_payments_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%payments}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "ID Бренда"',
            'shop_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "ID Магазина"',
            'service_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "ID услуги"',
            'type' => Schema::TYPE_INTEGER . '(1) NOT NULL COMMENT "Тип операции"',
            'way' => Schema::TYPE_INTEGER . '(1) NOT NULL COMMENT "Способ оплаты"',
            'date' => Schema::TYPE_DATE . ' NOT NULL COMMENT "Дата платежа"',
            'invoice_number' => Schema::TYPE_INTEGER . ' NULL COMMENT "Номер счета"',
            'invoice_date' => Schema::TYPE_DATE . ' NOT NULL COMMENT "Дата выставления счета"',
            'amount' => Schema::TYPE_DECIMAL . '(12,2) NOT NULL COMMENT "Сумма"',
            'description' => Schema::TYPE_TEXT . ' NULL COMMENT "Описание"',
            'status' => Schema::TYPE_INTEGER . '(1) NOT NULL COMMENT "Статус платежа"',
        ]);

        $this->addForeignKey('paymentsUserId', '{{%payments}}', 'user_id', '{{%user}}',
            'id');

        $this->addForeignKey('paymentsShopId', '{{%payments}}', 'shop_id', '{{%shops}}',
            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropForeignKey('paymentsShopId', '{{%payments}}');
        $this->dropForeignKey('paymentsUserId', '{{%payments}}');

        $this->dropTable('{{%payments}}');
    }
}
