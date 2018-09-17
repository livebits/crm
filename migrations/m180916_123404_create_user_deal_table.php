<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_deal`.
 */
class m180916_123404_create_user_deal_table extends Migration
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

        $this->createTable('user_deal', [
            'id' => $this->primaryKey(),

            'user_id' => $this->integer(),
            'deal_id' => $this->integer(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user_deal');
    }
}
