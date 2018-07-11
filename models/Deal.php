<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "deal".
 *
 * @property int $id
 * @property int $customer_id
 * @property string $subject
 * @property int $price
 * @property int $level
 * @property int $created_at
 * @property int $updated_at
 */
class Deal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'price', 'level'], 'integer'],
            [['subject'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'نام مشتری',
            'subject' => 'موضوع',
            'price' => 'قیمت',
            'level' => 'مرحله',
            'created_at' => 'تاریخ معامله',
            'updated_at' => 'Updated At',
        ];
    }
}
