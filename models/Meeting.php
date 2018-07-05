<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "meeting".
 *
 * @property int $id
 * @property int $user_id
 * @property int $customer_id
 * @property string $content
 * @property int $created_at
 * @property int $updated_at
 * @property int $next_date
 * @property int $rating
 */
class Meeting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meeting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'customer_id', 'rating'], 'integer'],
            [['content', 'created_at', 'updated_at', 'next_date'], 'string'],
            [['rating', 'content', 'created_at', 'next_date'], 'required', 'message' => 'لطفاً {attribute} را وارد نمایید.']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'کد جلسه',
            'user_id' => 'کاربر مسئول',
            'customer_id' => 'مشتری',
            'content' => 'توضیحات',
            'created_at' => 'تاریخ جلسه',
            'updated_at' => 'Updated At',
            'next_date' => 'تاریخ پیگیری بعدی',
            'rating' => 'میزان رضایت',
        ];
    }

    public function getImages()
    {
        return $this
            ->hasMany(Media::className(), ['meeting_id' => 'id'])
            ->andFilterWhere(['=', 'images.type', Media::$IMAGE]);
    }
}
