<?php

namespace app\models;

use Yii;
use app\behaviors\JDateTimeBehavior;

/**
 * This is the model class for table "customer".
 *
 * @property int $id
 * @property int $user_id
 * @property string $firstName
 * @property string $lastName
 * @property string $companyName
 * @property string $position
 * @property string $mobile
 * @property string $phone
 * @property string $source
 * @property string $description
 * @property string $image
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class Customer extends \yii\db\ActiveRecord
{

    public static $CLUE = 0;
    public static $CUSTOMER = 1;
    public static $DEALING = 2;
    public static $OFF_CUSTOMER = 3;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['JDateTimeBehavior'] = [
            'class' =>JDateTimeBehavior ::className(),
            'dateTimeAttributes' => ['date']
        ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'created_at', 'updated_at', 'source'], 'integer'],
            [['description', 'image'], 'string'],
            [['lastName', 'mobile', 'source'], 'required', 'message' => 'لطفاً {attribute} را وارد نمایید.'],
            [['firstName', 'lastName', 'companyName', 'position', 'mobile', 'phone'], 'string', 'max' => 255],
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
            'image' => 'تصویر',
            'firstName' => 'نام',
            'lastName' => 'نام خانوادگی',
            'companyName' => 'نام شرکت',
            'position' => 'سمت',
            'mobile' => 'موبایل',
            'phone' => 'تلفن',
            'source' => 'منبع',
            'description' => 'توضیحات',
            'status' => 'Status',
            'created_at' => 'تاریخ ثبت',
            'updated_at' => 'Updated At',
        ];
    }


    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if($this->isNewRecord) {
                $this->status = Customer::$CLUE;
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

    public function get_all_sources()
    {
        $sources = Source::find()->select(['id', 'name'])->all();
        $result = [];
        foreach ($sources as $source) {
            $result[$source['id']] = $source['name'];
        }

        return $result;
    }

    public static function getPassengerName($id)
    {
        $self = Customer::find()
            ->select([
                'id',
                'firstName',
                'lastName'
            ])
            ->where(['id' => $id])
            ->asArray()
            ->one();
        return $self['firstName'] . ' ' . $self['lastName'];
    }
}
