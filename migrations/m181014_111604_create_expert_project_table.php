<?php

use yii\db\Migration;

/**
 * Handles the creation of table `expert_project`.
 */
class m181014_111604_create_expert_project_table extends Migration
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

        $this->createTable('expert_project', [
            'id' => $this->primaryKey(),

            'project_id' => $this->integer(),
            'expert_id' => $this->integer(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('expert_project');
    }
}
