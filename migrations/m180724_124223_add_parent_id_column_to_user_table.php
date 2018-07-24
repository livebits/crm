<?php

use yii\db\Migration;

/**
 * Handles adding position to table `user`.
 */
class m180724_124223_add_parent_id_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'parent_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'parent_id');
    }
}
