<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "deal_event".
 *
 * @property int $id
 * @property int $user_id
 * @property int $deal_id
 * @property int $event_id
 * @property int $value
 * @property int $business_day
 * @property int $created_at
 * @property int $updated_at
 */
class DealEvent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deal_event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'deal_id', 'event_id', 'value', 'business_day', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if($this->isNewRecord) {
                $this->created_at = time();

            } else {
                $this->updated_at = time();
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'کاربر ثبت کننده',
            'deal_id' => 'موضوع قرارداد',
            'event_id' => 'رویداد',
            'value' => 'مقدار',
            'business_day' => 'روز کاری',
            'created_at' => 'تاریخ ثبت',
            'updated_at' => 'Updated At',
        ];
    }
}
