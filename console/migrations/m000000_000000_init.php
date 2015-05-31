<?php

use yii\db\Schema;
use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING . '',
            'first_name'=> Schema::TYPE_STRING . '(50)',
            'last_name'=> Schema::TYPE_STRING . '(50)',
            'sex'=> Schema::TYPE_STRING . '(10)',
            'photo'=> Schema::TYPE_STRING . '(255)',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING,
            'email' => Schema::TYPE_STRING . '',
            'phone' => Schema::TYPE_STRING . '(32) NOT NULL',

            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->createTable('{{%auth}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER. ' NOT NULL',
            'source' => Schema::TYPE_STRING . '(255) NOT NULL',
            'source_id' => Schema::TYPE_STRING . '(255) NOT NULL',
        ], $tableOptions);

        $this->createIndex('auth_unique', '{{%auth}}', ['source', 'source_id'], true);
        $this->addForeignKey('fk_auth_user', '{{%auth}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%images}}', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . '(255)',
            'description' => Schema::TYPE_STRING . '(4000)',
            'file' => Schema::TYPE_STRING . '(255)',
            'module' => Schema::TYPE_STRING . '(50)',
            'parent_id' => Schema::TYPE_INTEGER. ' NOT NULL',
            'position' => Schema::TYPE_INTEGER. ' NOT NULL',
            'main_flag' => Schema::TYPE_INTEGER. ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_by' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_by' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->insert('{{%user}}', ['id' => 1, 'username' => 'admin', 'auth_key' => 'c2WtXCAudH2jZPxSViPQUnhd5n3BV5kF', 'password_hash' => '$2y$13$UZ1z.yvPjAtOGQ1w7eBzYe.9EANNlQukGkrmvQWCZIN2lDSi0qHs2', 'email' => 'admin@yiindo.com']);

        /* use \vendor\yiisoft\yii2\rbac\migrations */
        //$this->insert('{{%auth_assigment}}', ['item_name'=>'admin','user_id' => 1]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%auth}}');
        $this->dropTable('{{%user}}');
    }
}
