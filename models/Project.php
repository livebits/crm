<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property string $title
 * @property int $programming_lang
 * @property string $description
 * @property int $created_at
 * @property int $updated_at
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['programming_lang', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'عنوان پروژه',
            'programming_lang' => 'زبان برنامه نویسی',
            'description' => 'توضیحات',
            'created_at' => 'تاریخ ثبت',
            'updated_at' => 'تاریخ بروزرسانی',
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

    public static function languages($selected_lang = null) {
        $langs = [
            0 => '',
            1 => 'اندروید',
            2 => 'ios',
            3 => 'php',
            4 => 'reactjs',
        ];

        if(!isset($selected_lang)) {
            return $langs;
        } else {
            return $langs[$selected_lang];
        }
    }
}
