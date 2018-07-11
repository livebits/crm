<?php

use yii\db\Migration;

/**
 * Handles adding position to table `meeting`.
 */
class m180710_181123_add_deal_id_column_to_meeting_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('meeting', 'deal_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('meeting', 'deal_id');
    }
}
