<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "deal_level".
 *
 * @property int $id
 * @property int $level_number
 * @property string $level_name
 * @property int $created_at
 * @property int $updated_at
 */
class DealLevel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deal_level';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level_number', 'created_at', 'updated_at'], 'integer'],
            [['level_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'level_number' => 'شماره مرحله',
            'level_name' => 'عنوان مرحله',
            'created_at' => 'زمان ثبت',
            'updated_at' => 'Updated At',
        ];
    }

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
}
