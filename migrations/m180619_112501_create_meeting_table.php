<?php

use yii\db\Migration;

/**
 * Handles the creation of table `meeting`.
 */
class m180619_112501_create_meeting_table extends Migration
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
        $this->createTable('meeting', [
            'id' => $this->primaryKey(),

            'user_id' => $this->integer(),
            'customer_id' => $this->integer(),

            'content' => $this->text(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'next_date' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('meeting');
    }
}
