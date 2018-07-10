<?php

use yii\db\Migration;

class m180710_094322_insert_source_menu_to_auth_item extends Migration
{
    public function up()
    {
        if(!\app\models\AuthItem::find()->where(['name' => '/source'])->exists()){
            $this->insert('auth_item', [
                'name' => '/source',
                'type' => 3,
                'description' => 'منبع',
                'position' => 5,
                'data' => 'fa-address-book'
            ]);
        }
    }

    public function down()
    {
        echo "m180205_114322_insert_menu_data_to_auth_item cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
