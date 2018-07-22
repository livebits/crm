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

    public function get_all_deal_levels()
    {
        $deal_levels = DealLevel::find()->select(['id', 'level_number', 'level_name'])->all();
        $result = [];
        foreach ($deal_levels as $deal_level) {
            $result[$deal_level['id']] = $deal_level['level_name'];
        }

        return $result;
    }
}
