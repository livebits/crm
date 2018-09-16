<?php

use yii\db\Migration;

/**
 * Handles adding position to table `ticket`.
 */
class m180915_171923_insert_attachment_column_to_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ticket', 'attachment', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ticket', 'attachment');
    }
}
