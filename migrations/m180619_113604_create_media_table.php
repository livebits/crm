<?php

use yii\db\Migration;

/**
 * Handles the creation of table `media`.
 */
class m180619_113604_create_media_table extends Migration
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

        $this->createTable('media', [
            'id' => $this->primaryKey(),

            'meeting_id' => $this->integer(),

            'name' => $this->string(),
            'type' => $this->string(),
            'filename' => $this->string(),
            'description' => $this->string(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('media');
    }
}
