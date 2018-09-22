<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property int $user_id
 * @property string $action
 * @property string $description
 * @property int $created_at
 */
class Log extends \yii\db\ActiveRecord
{

    /**
     * Action
     */
    const AddUserToDeal = 'add_user_to_deal';
    const AddExpertToDepartment = 'add_expert_to_department';
    const AddTicketForExpert = 'add_ticket_for_expert';
    const AddNewReceipt = 'add_new_receipt';
    const AddNewTicket = 'add_new_ticket';
    const ReplyTicket = 'reply_ticket';
    const CheckTicket = 'check_ticket';
    const CloseTicket = 'close_ticket';

    ////

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at'], 'integer'],
            [['action', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'کاربر',
            'action' => 'عمل',
            'description' => 'توضیحات',
            'created_at' => 'زمان',
        ];
    }

    public static function addLog($action, $description=''){

        $log = new Log();
        $log->user_id = Yii::$app->user->id;
        $log->action = $action;
        $log->description = $description;
        $log->created_at = time();

        $log->save();
    }
}
