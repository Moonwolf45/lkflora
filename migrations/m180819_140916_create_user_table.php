<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `user`.
 */
class m180819_140916_create_user_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'company_name' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Название бренда"',
            'email' => Schema::TYPE_STRING . ' UNIQUE NOT NULL COMMENT "E-mail"',
            'phone' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Телефон"',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Пароль"',
            'password_reset_token' => Schema::TYPE_STRING . ' UNIQUE COMMENT "Токен для восстановление пароля"',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL COMMENT "Ключ авторизации"',
            'avatar' => Schema::TYPE_STRING . ' NOT NULL DEFAULT "images/group.svg" COMMENT "Аватар"',
            'balance' => Schema::TYPE_DECIMAL . '(12,2) NOT NULL COMMENT "Баланс"',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10 COMMENT "Статус пользователя"',
            'role' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0 COMMENT "Роль"',
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%user}}');
    }

}
