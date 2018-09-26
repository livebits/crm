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
 * @property string $reply_to
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
    const NEED_CUSTOMER_REPLY = 2;
    const NEED_EXPERT_REPLY = 3;
    const PENDING = 4;
    const CLOSED = 5;
    ///////////////////////////
    ///
    /// Reply tickets statuses
    ///
    /// EXPERT -> expert replied
    /// CUSTOMER -> customer replied
    const EXPERT_REPLIED = 100;
    const CUSTOMER_REPLIED = 101;
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
            [['user_id', 'deal_id', 'department', 'status', 'reply_to', 'created_at', 'updated_at'], 'integer'],
            [['deal_id', 'department', 'body', 'title'], 'required'],
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
            'id' => 'کد تیکت',
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

    public static function ticketStatus($selected_status = null) {
        $status = [
            Ticket::NOT_CHECKED => 'بررسی نشده',
            Ticket::ANSWERED => 'پاسخ داده شده',
            Ticket::NEED_CUSTOMER_REPLY => 'منتظر پاسخ شما',
            Ticket::NEED_EXPERT_REPLY => 'در انتظار پاسخ کارشناس',
            Ticket::PENDING => 'در حال بررسی',
            Ticket::CLOSED => 'بسته شده',
        ];

        if(!isset($selected_status)) {
            return $status;
        } else {
            return $status[$selected_status];
        }
    }
}
