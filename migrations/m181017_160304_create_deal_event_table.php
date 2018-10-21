<?php

use yii\db\Migration;

/**
 * Handles the creation of table `deal_event`.
 */
class m181017_160304_create_deal_event_table extends Migration
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

        $this->createTable('deal_event', [
            'id' => $this->primaryKey(),

            'user_id' => $this->integer(),
            'deal_id' => $this->integer(),
            'event_id' => $this->integer(),
            'value' => $this->integer(),
            'business_day' => $this->integer(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('deal_event');
    }
}
