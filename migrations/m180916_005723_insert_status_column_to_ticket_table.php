<?php

use yii\db\Migration;

/**
 * Handles adding position to table `ticket`.
 */
class m180916_005723_insert_status_column_to_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ticket', 'status', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ticket', 'status');
    }
}
