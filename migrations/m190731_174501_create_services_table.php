<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%services}}`.
 */
class m190731_174501_create_services_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%services}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "ID Бренда"',
            'shop_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "ID Магазина"',
            'tariff_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "ID Тарифа"',
            'addition_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "ID Допа"',
            'service_payment_date' => Schema::TYPE_DATE . ' NOT NULL COMMENT "Дата списания плятежа"',
        ]);

        $this->addForeignKey('servicesUserId', '{{%services}}', 'user_id', '{{%user}}',
            'id', 'CASCADE');

        $this->addForeignKey('servicesShopId', '{{%services}}', 'shop_id', '{{%shops}}',
            'id');

        $this->addForeignKey('servicesTariffId', '{{%services}}', 'tariff_id', '{{%tariff}}',
            'id');

        $this->addForeignKey('servicesAdditionId', '{{%services}}', 'addition_id', '{{%addition}}',
            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropForeignKey('servicesAdditionId', '{{%services}}');
        $this->dropForeignKey('servicesTariffId', '{{%services}}');
        $this->dropForeignKey('servicesShopId', '{{%services}}');
        $this->dropForeignKey('servicesUserId', '{{%services}}');

        $this->dropTable('{{%services}}');
    }
}
