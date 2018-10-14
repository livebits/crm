<?php

use yii\db\Migration;

/**
 * Handles adding position to table `insert_user_id_column_to_project_info_table`.
 */
class m181014_123223_insert_user_id_column_to_project_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('project_info', 'user_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('project_info', 'user_id');
    }
}
