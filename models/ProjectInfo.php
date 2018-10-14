<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project_info".
 *
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property string $publish_version
 * @property string $package_name
 * @property string $sign_file
 * @property string $keystore
 * @property string $api_key
 * @property string $key_alias_password
 * @property int $created_at
 * @property int $updated_at
 */
class ProjectInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id'], 'required'],
            [['project_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['publish_version', 'package_name', 'sign_file', 'keystore', 'api_key', 'key_alias_password'], 'string', 'max' => 255],
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
                $this->user_id = Yii::$app->user->id;

            } else {
                $this->updated_at = time();
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'نام پروژه',
            'user_id' => 'کاربر ثبت کننده',
            'publish_version' => 'ورژن خروجی گرفته شده',
            'package_name' => 'نام پکیج',
            'sign_file' => 'فایل sign',
            'keystore' => 'رمز Keystore',
            'api_key' => 'Api Key',
            'key_alias_password' => 'رمز Key Alias',
            'created_at' => 'تاریخ ثبت',
            'updated_at' => 'تاریخ بروزرسانی',
        ];
    }
}
