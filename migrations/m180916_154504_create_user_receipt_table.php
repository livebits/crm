<?php

use yii\db\Migration;

/**
 * Handles the creation of table `receipt`.
 */
class m180916_154504_create_user_receipt_table extends Migration
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

        $this->createTable('receipt', [
            'id' => $this->primaryKey(),

            'user_id' => $this->integer(),
            'bank_id' => $this->integer(),
            'amount' => $this->string(),
            'receipt_number' => $this->string(),
            'description' => $this->string(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('receipt');
    }
}
