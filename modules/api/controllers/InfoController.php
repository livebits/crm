<?php

namespace app\modules\api\controllers;

use app\models\ExpertDepartment;
use app\models\ExpertTicket;
use app\models\TicketSearch;
use app\models\UserDeal;
use Yii;
use app\components\ApiComponent;
use app\models\Customer;
use app\models\Deal;
use app\models\Meeting;
use app\models\Ticket;
use webvimark\modules\UserManagement\models\User;
use yii\db\Query;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class InfoController extends \yii\rest\Controller
{
    public function beforeAction($action)
    {
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
                QueryParamAuth::className(),
            ],
        ];

        return $behaviors;
    }

    /**
     * @api {post} /info/stats 10- user dashboard
     * @apiName 10.user dashboard
     * @apiGroup User
     *
     *
     * @apiSuccess {Array} data response data.
     * @apiSuccess {String} data.clues_count clues count.
     * @apiSuccess {String} data.customers_count customers count.
     * @apiSuccess {String} data.deals_count deals count.
     * @apiSuccess {String} data.contacts_count contacts count.
     * @apiSuccess {String} data.off_customer off customers count.
     * @apiSuccess {String} data.lateCustomerMeetingsCount late customer meetings count.
     * @apiSuccess {String} data.lateDealMeetingsCount late deal meetings count.
     * @apiSuccess {String} message response message.
     * @apiSuccess {Integer} code response code [0: failure, 1: success].
     * @apiSuccess {Integer} status response status code [see -status table-].
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       {
     *           "data": [
     *               {
     *                   "clues_count": 2,
     *                   "customers_count": 3,
     *                   "deals_count": 7,
     *                   "contacts_count": 7,
     *                   "off_customer": 2,
     *                   "lateCustomerMeetingsCount": 3,
     *                   "lateDealMeetingsCount": 4
     *               }
     *           ],
     *           "message": "",
     *           "code": 1,
     *           "status": 200
     *       }
     *
     *
     */
    public function actionStats()
    {
        $user = User::getCurrentUser();
        if(\Yii::$app->user->isSuperadmin  || $user::hasRole(['Admin'], $superAdminAllowed = true)) {
            $customers = Customer::find()->all();

        } else if ($user::hasRole(['manager'])) {
            $my_users = (new Query())
                ->select('id')
                ->from('user')
                ->where('id=' . Yii::$app->user->id)
                ->orWhere('parent_id=' . Yii::$app->user->id)
                ->all();

            $my_users_ids = [];
            $my_users_ids[0] = \Yii::$app->user->id;
            foreach ($my_users as $my_user) {
                $my_users_ids[] = $my_user['id'];
            }
            $my_users_ids = implode(',', $my_users_ids);

            $customers = Customer::find()
                ->where('user_id IN (' . $my_users_ids . ')')
                ->all();

        } else {
            $customers = Customer::find()
                ->where('user_id=' . \Yii::$app->user->id)
                ->all();
        }

        $clues_count = $customers_count = $deals_count = $off_customer = $contacts_count = 0;

        $my_customer_ids = [];
        foreach ($customers as $customer) {
            if ($customer->status == Customer::$CLUE) {
                $clues_count++;
            } else if ($customer->status == Customer::$CUSTOMER) {
                $customers_count++;
            } else if ($customer->status == Customer::$OFF_CUSTOMER) {
                $off_customer++;
            }

            $my_customer_ids[] = $customer->id;
        }
        $contacts_count = $clues_count + $customers_count + $off_customer;

        $my_customer_ids = implode(",", $my_customer_ids);
        if($my_customer_ids == ""){
            $my_customer_ids = "-1";
        }

        $deals = Deal::find()
            ->select('id')
            ->where('customer_id IN (' . $my_customer_ids . ')')
            ->asArray()
            ->all();

        $deals_ids = [];
        foreach ($deals as $deal) {
            $deals_ids[] = $deal['id'];
        }
        $deals_ids = implode(',', $deals_ids);
        if($deals_ids == "") {
            $deals_ids = "-1";
        }

        $deals_count = count($deals);

        //check meetings
        $customers_meetings = Meeting::find()
            ->where('deal_id IS NULL')
            ->andWhere('next_date IS NOT NULL')
            ->andWhere('customer_id IN (' . $my_customer_ids . ')')
            ->orderBy('created_at DESC')
            ->all();

        $lateCustomerMeetingsCount = 0;
        $customer_ids = [];
        foreach ($customers_meetings as $customers_meeting) {

            if (in_array($customers_meeting->customer_id, $customer_ids)) {
                continue;
            }

            $customer_ids[] = $customers_meeting->customer_id;
            if(date('Y-m-d', $customers_meeting->next_date) < date('Y-m-d', time())){

                $lateCustomerMeetingsCount++;
            }
        }

        $deals_meetings = Meeting::find()
            ->where('customer_id IS NULL')
            ->andWhere('next_date IS NOT NULL')
            ->andWhere('deal_id IN (' . $deals_ids . ')')
            ->orderBy('created_at DESC')
            ->all();

        $lateDealMeetingsCount = 0;
        $deal_ids = [];
        foreach ($deals_meetings as $deals_meeting) {

            if (in_array($deals_meeting->deal_id, $deal_ids)) {
                continue;
            }

            $deal_ids[] = $deals_meeting->deal_id;
            if(date('Y-m-d', $deals_meeting->next_date) < date('Y-m-d', time())){

                $lateDealMeetingsCount++;
            }
        }

        $data = [
            'clues_count' => $clues_count,
            'customers_count' => $customers_count,
            'deals_count' => $deals_count,
            'contacts_count' => $contacts_count,
            'off_customer' => $off_customer,
            'lateCustomerMeetingsCount' => $lateCustomerMeetingsCount,
            'lateDealMeetingsCount' => $lateDealMeetingsCount,
        ];

        return ApiComponent::successResponse('', $data);
    }

    public function actionCustomerStats()
    {
        $all_tickets = $done_tickets = $in_progress_tickets = $waiting_tickets = $dept_amount = $current_deals = 0;

        $all_tickets = Ticket::find()
            ->where('user_id=' . Yii::$app->user->id)
            ->andWhere('reply_to IS NULL')
            ->count();
        $done_tickets = Ticket::find()
            ->where('user_id=' . Yii::$app->user->id)
            ->andWhere('status=' . Ticket::CLOSED)
            ->count();
        $in_progress_tickets = $all_tickets - $done_tickets;
        $waiting_tickets = Ticket::find()
            ->where('user_id=' . Yii::$app->user->id)
            ->andWhere('status=' . Ticket::NEED_CUSTOMER_REPLY)
            ->count();
        $current_deals = UserDeal::find()
            ->where('user_id=' . Yii::$app->user->id)
            ->count();

        $data = [
            'all_tickets' => $all_tickets,
            'done_tickets' => $done_tickets,
            'in_progress_tickets' => $in_progress_tickets,
            'waiting_tickets' => $waiting_tickets,
            'dept_amount' => $dept_amount,
            'current_deals' => $current_deals,
        ];

        return ApiComponent::successResponse('', $data, true);
    }

    public function actionExpertStats()
    {
        $searchModel = new TicketSearch();

        $params = Yii::$app->request->queryParams;

        //find expert department
        $expertDeps = ExpertDepartment::find()
            ->select('department_id')
            ->where('expert_id=' . Yii::$app->user->id)
            ->asArray()
            ->all();
        $deps = [];
        $deps[] = -1;
        foreach ($expertDeps as $expertDep) {
            $deps[] = intval($expertDep['department_id']);
        }

        //expert tickets
        $expertTickets = ExpertTicket::find()
            ->select('ticket_id')
            ->where('expert_id=' . Yii::$app->user->id)
            ->asArray()
            ->all();
        $tickets_id = [];
        $tickets_id[] = -1;
        foreach ($expertTickets as $expertTicket) {
            $tickets_id[] = intval($expertTicket['ticket_id']);
        }
        $dataProvider = $searchModel->search($params, $deps, $tickets_id);

        $data = [
            'all_tickets' => $dataProvider->getTotalCount()
        ];

        return ApiComponent::successResponse('', $data, true);
    }

    public function actionAdminStats()
    {
        $customers = Customer::find()->all();

        $clues_count = $customers_count = $deals_count = $off_customer = $contacts_count = 0;

        $my_customer_ids = [];
        foreach ($customers as $customer) {
            if ($customer->status == Customer::$CLUE) {
                $clues_count++;
            } else if ($customer->status == Customer::$CUSTOMER) {
                $customers_count++;
            } else if ($customer->status == Customer::$OFF_CUSTOMER) {
                $off_customer++;
            }

            $my_customer_ids[] = $customer->id;
        }
        $contacts_count = $clues_count + $customers_count + $off_customer;

        $my_customer_ids = implode(",", $my_customer_ids);
        if($my_customer_ids == ""){
            $my_customer_ids = "-1";
        }

        $deals = Deal::find()
            ->select('id')
            ->where('customer_id IN (' . $my_customer_ids . ')')
            ->asArray()
            ->all();

        $deals_ids = [];
        foreach ($deals as $deal) {
            $deals_ids[] = $deal['id'];
        }
        $deals_ids = implode(',', $deals_ids);
        if($deals_ids == "") {
            $deals_ids = "-1";
        }

        $deals_count = count($deals);

        //check meetings
        $customers_meetings = Meeting::find()
            ->leftJoin('customer', 'customer.id=meeting.customer_id')
            ->where('deal_id IS NULL')
            ->andWhere('next_date IS NOT NULL')
            ->andWhere('customer_id IN (' . $my_customer_ids . ')')
            ->andWhere('customer.status != ' . Customer::$OFF_CUSTOMER)
            ->orderBy('created_at DESC')
            ->all();

        $lateCustomerMeetingsCount = 0;
        $customer_ids = [];
        foreach ($customers_meetings as $customers_meeting) {

            if (in_array($customers_meeting->customer_id, $customer_ids)) {
                continue;
            }

            $customer_ids[] = $customers_meeting->customer_id;
            if(date('Y-m-d', $customers_meeting->next_date) < date('Y-m-d', time())){

                $lateCustomerMeetingsCount++;
            }
        }

        $deals_meetings = Meeting::find()
            ->where('customer_id IS NULL')
            ->andWhere('next_date IS NOT NULL')
            ->andWhere('deal_id IN (' . $deals_ids . ')')
            ->orderBy('created_at DESC')
            ->all();

        $lateDealMeetingsCount = 0;
        $deal_ids = [];
        foreach ($deals_meetings as $deals_meeting) {

            if (in_array($deals_meeting->deal_id, $deal_ids)) {
                continue;
            }

            $deal_ids[] = $deals_meeting->deal_id;
            if(date('Y-m-d', $deals_meeting->next_date) < date('Y-m-d', time())){

                $lateDealMeetingsCount++;
            }
        }

        $data = [
            'cluesCount' => $clues_count,
            'customersCount' => $customers_count,
            'dealsCount' => $deals_count,
            'contactsCount' => $contacts_count,
            'offCustomer' => $off_customer,
            'lateCustomerMeetingsCount' => $lateCustomerMeetingsCount,
            'lateDealMeetingsCount' => $lateDealMeetingsCount,
        ];

        return ApiComponent::successResponse('', $data, true);
    }
}