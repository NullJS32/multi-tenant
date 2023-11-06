<?php

use yii\db\Migration;

/**
 * Создание таблицы users
 */
class m231105_125759_create_master_database extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
        ]);

        $this->insert('{{%users}}', [
            'username' => 'user1',
            'password' => Yii::$app->security->generatePasswordHash('1234'),
        ]);

        $this->insert('{{%users}}', [
            'username' => 'user2',
            'password' => Yii::$app->security->generatePasswordHash('1234'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('users');
    }
}

