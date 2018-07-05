<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "media".
 *
 * @property int $id
 * @property int $meeting_id
 * @property string $name
 * @property string $type
 * @property string $filename
 * @property string $description
 * @property int $created_at
 * @property int $updated_at
 */
class Media extends \yii\db\ActiveRecord
{
    public static $AUDIO = "AUDIO";
    public static $IMAGE = "IMAGE";
    public static $OTHER = "OTHER";

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'media';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['meeting_id', 'created_at', 'updated_at'], 'integer'],
            [['name', 'type', 'filename', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'meeting_id' => 'جلسه',
            'name' => 'عنوان',
            'type' => 'نوع فایل',
            'filename' => 'نام فایل',
            'description' => 'توضیحات',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
