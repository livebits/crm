<?php

use yii\db\Migration;

/**
 * Handles the creation of table `log`.
 */
class m180917_130204_create_log_table extends Migration
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

        $this->createTable('log', [
            'id' => $this->primaryKey(),

            'user_id' => $this->integer(),
            'action' => $this->string(),
            'description' => $this->string(),

            'created_at' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log');
    }
}
