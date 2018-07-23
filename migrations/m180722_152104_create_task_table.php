<?php

use yii\db\Migration;

/**
 * Handles the creation of table `task`.
 */
class m180722_152104_create_task_table extends Migration
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

        $this->createTable('task', [
            'id' => $this->primaryKey(),

            'user_id' => $this->integer(),
            'customer_id' => $this->integer(),
            'deal_id' => $this->integer(),

            'name' => $this->string(),
            'is_done' => $this->integer(),

            'created_at' => $this->integer(),
            'expired_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('task');
    }
}
