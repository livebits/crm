<?php

use yii\db\Migration;

/**
 * Handles the creation of table `project`.
 */
class m181014_110504_create_projects_table extends Migration
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

        $this->createTable('project', [
            'id' => $this->primaryKey(),

            'title' => $this->string(),
            'programming_lang' => $this->integer(),
            'description' => $this->text(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('project');
    }
}
