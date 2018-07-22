<?php

use yii\db\Migration;

/**
 * Handles the creation of table `deal_level`.
 */
class m180721_155104_create_deal_level_table extends Migration
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

        $this->createTable('deal_level', [
            'id' => $this->primaryKey(),

            'level_number' => $this->integer(),
            'level_name' => $this->string(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('deal_level');
    }
}
