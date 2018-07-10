<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "source".
 *
 * @property int $id
 * @property string $name
 * @property int $created_at
 * @property int $updated_at
 */
class Source extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'source';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'نام منبع',
            'created_at' => 'Created At',
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
