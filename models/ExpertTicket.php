<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "expert_ticket".
 *
 * @property int $id
 * @property int $expert_id
 * @property int $ticket_id
 * @property int $created_at
 * @property int $updated_at
 */
class ExpertTicket extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expert_ticket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expert_id', 'ticket_id', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'expert_id' => 'کارشناس',
            'ticket_id' => 'تیکت',
            'created_at' => 'تاریخ ثبت',
            'updated_at' => 'Updated At',
        ];
    }
}
