<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ticket".
 *
 * @property int $id
 * @property int $user_id
 * @property int $deal_id
 * @property int $department
 * @property string $title
 * @property string $body
 * @property string $status
 * @property string $attachment
 * @property int $created_at
 * @property int $updated_at
 */
class Ticket extends \yii\db\ActiveRecord
{
    /*
     * Status
     */
    const NOT_CHECKED = 0;
    const ANSWERED = 1;
    const NEED_REPLY = 2;
    const CLOSED = 3;
    ///////////////////////////

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'deal_id', 'department', 'status', 'created_at', 'updated_at'], 'integer'],
            [['deal_id', 'department', 'body'], 'required'],
            [['body'], 'string'],
            [['title', 'attachment'], 'string', 'max' => 255],
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
            'deal_id' => 'سفارش مرتبط',
            'department' => 'واحد',
            'title' => 'عنوان',
            'body' => 'متن',
            'status' => 'وضعیت',
            'attachment' => 'ضمیمه',
            'created_at' => 'تاریخ ثبت',
            'updated_at' => 'تاریخ بروز رسانی',
        ];
    }
}
