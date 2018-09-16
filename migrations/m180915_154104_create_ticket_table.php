<?php

use yii\db\Migration;

/**
 * Handles the creation of table `ticket`.
 */
class m180915_154104_create_ticket_table extends Migration
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

        $this->createTable('ticket', [
            'id' => $this->primaryKey(),

            'user_id' => $this->integer(),
            'deal_id' => $this->integer(),
            'department' => $this->integer(),

            'title' => $this->string(),
            'body' => $this->text(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('ticket');
    }
}
