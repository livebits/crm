<?php

use yii\db\Migration;

/**
 * Handles the creation of table `expert_department`.
 */
class m180919_174404_create_expert_department_table extends Migration
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

        $this->createTable('expert_department', [
            'id' => $this->primaryKey(),

            'expert_id' => $this->integer(),
            'department_id' => $this->integer(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('expert_department');
    }
}
