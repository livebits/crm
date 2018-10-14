<?php

use yii\db\Migration;

/**
 * Handles the creation of table `project_info`.
 */
class m181014_111104_create_project_info_table extends Migration
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

        $this->createTable('project_info', [
            'id' => $this->primaryKey(),

            'project_id' => $this->integer(),
            'publish_version' => $this->string(),
            'package_name' => $this->string(),
            'sign_file' => $this->string(),
            'keystore' => $this->string(),
            'api_key' => $this->string(),
            'key_alias_password' => $this->string(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('project_info');
    }
}
