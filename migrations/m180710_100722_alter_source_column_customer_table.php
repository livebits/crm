<?php

use yii\db\Migration;

class m180710_100722_alter_source_column_customer_table extends Migration
{
    public function up()
    {
        $this->alterColumn('customer', 'source', $this->integer());
    }

    public function down()
    {
        $this->alterColumn('customer','source', $this->string());
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
