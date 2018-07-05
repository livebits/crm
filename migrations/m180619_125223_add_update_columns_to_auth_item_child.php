<?php

use yii\db\Migration;

/**
 * Handles adding position to table `auth_item_child`.
 */
class m180619_125223_add_update_columns_to_auth_item_child extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('auth_item_child', 'can_add', $this->boolean());
        $this->addColumn('auth_item_child', 'can_edit', $this->boolean());
        $this->addColumn('auth_item_child', 'can_delete', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('auth_item_child', 'can_add');
        $this->dropColumn('auth_item_child', 'can_edit');
        $this->dropColumn('auth_item_child', 'can_delete');
    }
}
