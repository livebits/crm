<?php

use yii\db\Migration;

/**
 * Handles the creation of table `expert_ticket`.
 */
class m180920_172304_create_expert_ticket_table extends Migration
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

        $this->createTable('expert_ticket', [
            'id' => $this->primaryKey(),

            'expert_id' => $this->integer(),
            'ticket_id' => $this->integer(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('expert_ticket');
    }
}
