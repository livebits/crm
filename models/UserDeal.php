<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_deal".
 *
 * @property int $id
 * @property int $user_id
 * @property int $deal_id
 * @property int $created_at
 * @property int $updated_at
 */
class UserDeal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_deal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'deal_id', 'created_at', 'updated_at'], 'integer'],
            [['user_id', 'deal_id'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'مشتری',
            'deal_id' => 'قرارداد',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
