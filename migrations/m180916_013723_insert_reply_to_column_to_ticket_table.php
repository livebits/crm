<?php

use yii\db\Migration;

/**
 * Handles adding position to table `ticket`.
 */
class m180916_013723_insert_reply_to_column_to_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ticket', 'reply_to', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ticket', 'reply_to');
    }
}
