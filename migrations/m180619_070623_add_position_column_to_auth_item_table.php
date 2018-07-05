<?php

use yii\db\Migration;

/**
 * Handles adding position to table `auth_item`.
 */
class m180619_070623_add_position_column_to_auth_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('auth_item', 'position', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('auth_item', 'position');
    }
}
