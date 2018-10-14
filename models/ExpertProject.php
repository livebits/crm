<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "expert_project".
 *
 * @property int $id
 * @property int $project_id
 * @property int $expert_id
 * @property int $created_at
 * @property int $updated_at
 */
class ExpertProject extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expert_project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id', 'expert_id', 'created_at', 'updated_at'], 'integer'],
            [['project_id', 'expert_id'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'نام پروژه',
            'expert_id' => 'نام کارشناس',
            'created_at' => 'تاریخ ثبت',
            'updated_at' => 'Updated At',
        ];
    }
}
