<?php

use yii\db\Expression;
use yii\db\Migration;

/**
 * Добавление администратора
 */
class m231105_135759_add_admin_in_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%auth_item}}', [
            'name' => 'admin',
            'type' => 1,
            'created_at' => new Expression('UNIX_TIMESTAMP()'),
            'updated_at' => new Expression('UNIX_TIMESTAMP()'),
        ]);
        $this->insert('{{%auth_assignment}}', [
            'item_name' => 'admin',
            'user_id' => 1,
            'created_at' => new Expression('UNIX_TIMESTAMP()'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%auth_item}}', "name='admin' and type=1");
        $this->delete('{{%auth_assignment}}', "item_name='admin' and user_id=1");
    }
}