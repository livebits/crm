<?php

use yii\db\Migration;

/**
 * Handles adding position to table `meeting`.
 */
class m180710_111923_add_image_column_to_customer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('customer', 'image', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('customer', 'image');
    }
}
