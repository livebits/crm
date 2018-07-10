<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_profile`.
 */
class m180710_145804_create_user_profile_table extends Migration
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

        $this->createTable('user_profile', [
            'id' => $this->primaryKey(),

            'user_id' => $this->integer(),

            'firstName' => $this->string(),
            'lastName' => $this->string(),
            'image' => $this->string(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user_profile');
    }
}
