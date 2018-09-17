<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "receipt".
 *
 * @property int $id
 * @property int $user_id
 * @property int $bank_id
 * @property string $amount
 * @property string $receipt_number
 * @property string $description
 * @property int $created_at
 * @property int $updated_at
 */
class Receipt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'receipt';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'bank_id', 'created_at', 'updated_at'], 'integer'],
            [['bank_id', 'amount', 'receipt_number'], 'required'],
            [['amount', 'receipt_number', 'description'], 'string', 'max' => 255],
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
            'bank_id' => 'بانک',
            'amount' => 'مبلغ واریزی',
            'receipt_number' => 'شماره فیش',
            'description' => 'توضیحات بیشتر',
            'created_at' => 'تاریخ ثبت',
            'updated_at' => 'Updated At',
        ];
    }
    public static function banks($selected_bank = null) {
        $banks = [
            1 => 'ملی',
            2 => 'تجارت',
            3 => 'صادرات',
            4 => 'رفاه',
        ];

        if(!isset($selected_bank)) {
            return $banks;
        } else {
            return $banks[$selected_bank];
        }
    }
}
