<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "expert_department".
 *
 * @property int $id
 * @property int $expert_id
 * @property int $department_id
 * @property int $created_at
 * @property int $updated_at
 */
class ExpertDepartment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expert_department';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expert_id', 'department_id', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'expert_id' => 'کارشناس',
            'department_id' => 'واحد',
            'created_at' => 'تاریخ ثبت',
            'updated_at' => 'تاریخ بروزرسانی',
        ];
    }
}
