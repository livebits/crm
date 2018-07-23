<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property int $user_id
 * @property int $customer_id
 * @property int $deal_id
 * @property string $name
 * @property int $is_done
 * @property int $created_at
 * @property int $expired_at
 * @property int $updated_at
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'customer_id', 'deal_id', 'is_done', 'created_at', 'expired_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'customer_id' => 'Customer ID',
            'deal_id' => 'Deal ID',
            'name' => 'Name',
            'is_done' => 'Is Done',
            'created_at' => 'Created At',
            'expired_at' => 'Expired At',
            'updated_at' => 'Updated At',
        ];
    }
}
