<?php

use yii\db\Schema;
use yii\db\Migration;

class m150317_161236_module_catalog_init extends Migration
{


    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%catalog_categories}}', [
            'id' => Schema::TYPE_PK,
            'code' =>  Schema::TYPE_STRING . '(50)',
            'title' => Schema::TYPE_STRING . '(50)',
            'slug' => Schema::TYPE_STRING . '(50)',
            'ordering' => Schema::TYPE_INTEGER.' NOT NULL',
            'disabled' => Schema::TYPE_INTEGER.' NOT NULL',
            'parent_id' => Schema::TYPE_INTEGER
        ], $tableOptions);

        $this->createTable('{{%catalog_measures}}', [
            'id' => Schema::TYPE_PK,
            'type' =>  Schema::TYPE_STRING . '(50)',
            'title' => Schema::TYPE_STRING . '(50)',
            'disabled' => Schema::TYPE_INTEGER.' NOT NULL'
        ], $tableOptions);

        $this->createTable('{{%catalog_category_attributes}}', [
            'id' => Schema::TYPE_PK,
            'category_id' =>  Schema::TYPE_INTEGER.' NOT NULL',
            'code' =>  Schema::TYPE_STRING . '(50)',
            'title' => Schema::TYPE_STRING . '(50)',
            'filter_type' => Schema::TYPE_STRING.'(50)',
            'measure' => Schema::TYPE_STRING.'(10)',
            'ordering' => Schema::TYPE_INTEGER.' NOT NULL',
            'disabled' => Schema::TYPE_INTEGER.' NOT NULL'
        ], $tableOptions);

        $this->createTable('{{%catalog}}', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . '(50)',
            'content' => Schema::TYPE_TEXT,
            'cost' => Schema::TYPE_FLOAT . ' NOT NULL',
            'category_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'author_id' => Schema::TYPE_INTEGER. ' NOT NULL',
            'status' => Schema::TYPE_INTEGER.' NOT NULL',
        ], $tableOptions);

        $this->createTable('{{%catalog_attribute_values}}', [
            'id' => Schema::TYPE_PK,
            'catalog_id' => Schema::TYPE_INTEGER.' NOT NULL',
            'attribute_id' => Schema::TYPE_INTEGER.' NOT NULL',
            'value' => Schema::TYPE_STRING . '(50) NOT NULL',
        ], $tableOptions);


        $this->insert('{{%catalog_categories}}', ['id' => 1, 'title' => 'Электроника', 'slug' => 'electronic',]);
        $this->insert('{{%catalog_categories}}', ['id' => 2, 'title' => 'Телефоны', 'slug' => 'phones', 'parent_id' => 1, ]);
        $this->insert('{{%catalog_categories}}', ['id' => 3, 'title' => 'Nokia Phones', 'slug' => 'phones-nokia', 'parent_id' => 2, ]);
        $this->insert('{{%catalog_categories}}', ['id' => 4, 'title' => 'Samsung Phones', 'slug' => 'phones-samsung', 'parent_id' => 2, ]);
        $this->insert('{{%catalog_categories}}', ['id' => 5, 'title' => 'Одежда', 'slug' => 'weather', ]);

        $this->insert('{{%catalog_category_attributes}}', ['id' => 1, 'category_id' => 3,'code' => 'model', 'filter_type'=> 'TEXT', 'title' => 'Модель']);
        $this->insert('{{%catalog_category_attributes}}', ['id' => 2, 'category_id' => 3,'code' => 'color', 'filter_type'=> 'CHECKBOX', 'title' => 'Цвет']);
        $this->insert('{{%catalog_category_attributes}}', ['id' => 3, 'category_id' => 3,'code' => 'weight','filter_type'=> 'RANGE', 'title' => 'Вес']);

        $this->insert('{{%catalog}}', ['id' => 1, 'title' => 'Nokia 3310', 'cost' => 100, 'category_id' => 3,]);
        $this->insert('{{%catalog}}', ['id' => 2, 'title' => 'Nokia N8', 'cost' => 150 , 'category_id' => 3,]);
        $this->insert('{{%catalog}}', ['id' => 3, 'title' => 'Samsung Galaxy', 'cost' => 130 , 'category_id' => 4,]);

        $this->insert('{{%catalog_attribute_values}}', ['id' => 11, 'catalog_id' => 2, 'attribute_id' => 1, 'value' => 'N8',]);
        $this->insert('{{%catalog_attribute_values}}', ['id' => 12, 'catalog_id' => 2, 'attribute_id' => 2, 'value' => 'Красный',]);


        $this->createTable('{{%gallery_albums}}', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING.'(255) NOT NULL',
            'content' => Schema::TYPE_TEXT,
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%gallery_albums}}');

        $this->dropTable('{{%catalog_attribute_values}}');
        $this->dropTable('{{%catalog}}');
        $this->dropTable('{{%catalog_category_attributes}}');
        $this->dropTable('{{%catalog_categories}}');
        $this->dropTable('{{%catalog_measures}}');
    }

}
