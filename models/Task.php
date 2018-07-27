<?php

namespace app\models;

use Yii;
use yii\db\Query;

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

    public static function getCustomerTasksStatus($customer_id) {
        $query = (new Query())
            ->select([
                'COUNT(CASE WHEN t.is_done=1 THEN 1 END) as doneTasks',
                'COUNT(t.id) as allTasks'])
            ->from('task as t')
            ->where('customer_id="' . $customer_id . '"')
            ->all();

        return  $query[0]['allTasks'] . ' / ' . $query[0]['doneTasks'];
    }

    public static function getDealTasksStatus($deal_id) {
        $query = (new Query())
            ->select([
                'COUNT(CASE WHEN t.is_done=1 THEN 1 END) as doneTasks',
                'COUNT(t.id) as allTasks'])
            ->from('task as t')
            ->where('deal_id="' . $deal_id . '"')
            ->all();

        return  $query[0]['allTasks'] . ' / ' . $query[0]['doneTasks'];
    }
}
