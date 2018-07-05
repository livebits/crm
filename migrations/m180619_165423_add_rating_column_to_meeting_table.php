<?php

use yii\db\Migration;

/**
 * Handles adding position to table `meeting`.
 */
class m180619_165423_add_rating_column_to_meeting_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('meeting', 'rating', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('meeting', 'rating');
    }
}
