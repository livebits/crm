<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "event".
 *
 * @property int $id
 * @property string $name
 * @property int $priority
 * @property int $created_at
 * @property int $updated_at
 */
class Event extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['priority', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
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

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'عنوان رویداد',
            'priority' => 'اولویت',
            'created_at' => 'تاریخ  ثبت',
            'updated_at' => 'Updated At',
        ];
    }

    public static function getDealLastEvent($deal_id) {
        
        $query = (new Query())
            ->select(['event.id' ,'event.name as lastEvent'])
            ->from('deal_event')
            ->leftJoin('event', 'deal_event.event_id=event.id')
            ->where('deal_event.deal_id=' . $deal_id)
            ->orderBy('deal_event.value DESC')
            ->one();

        return $query['lastEvent'];
    }
}
