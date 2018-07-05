<?php

use yii\db\Migration;

/**
 * Handles the creation of table `customer`.
 */
class m180619_104355_create_customer_table extends Migration
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

        $this->createTable('customer', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),

            'firstName' => $this->string(),
            'lastName' => $this->string(),
            'companyName' => $this->string(),
            'position' => $this->string(),
            'mobile' => $this->string(),
            'phone' => $this->string(),
            'source' => $this->string(),

            'description' => $this->text(),

            'status' => $this->integer(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('customer');
    }
}
