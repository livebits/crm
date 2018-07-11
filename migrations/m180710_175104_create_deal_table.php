<?php

use yii\db\Migration;

/**
 * Handles the creation of table `deal`.
 */
class m180710_175104_create_deal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('deal', [
            'id' => $this->primaryKey(),

            'customer_id' => $this->integer(),

            'subject' => $this->string(),
            'price' => $this->integer(),
            'level' => $this->integer(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('deal');
    }
}
