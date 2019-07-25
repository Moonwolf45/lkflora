<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m180819_140916_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id'                   => $this->primaryKey(),
            'email'                => $this->string()->notNull()->unique(),
            'username'             => $this->string()->notNull(),
            'password_hash'        => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'auth_key'             => $this->string(32)->notNull(),
            'status'               => $this->smallInteger()->notNull()->defaultValue(10),
            'city'                 => $this->string()->null(),
            'created_at'           => $this->integer()->notNull(),
            'updated_at'           => $this->integer()->notNull(),
        ]);

        $this->addCommentOnColumn('user', 'email', 'E-mail');
        $this->addCommentOnColumn('user', 'username', 'Имя пользователя');
        $this->addCommentOnColumn('user', 'password_hash', 'Пароль пользователя');
        $this->addCommentOnColumn('user', 'password_reset_token', 'Токен на восстановление пароля');
        $this->addCommentOnColumn('user', 'city', 'Город пользователя');
        $this->addCommentOnColumn('user', 'status', 'Статус пользователя');
        $this->addCommentOnColumn('user', 'auth_key', 'Уникальный ключ авторизации');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
