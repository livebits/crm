<?php

namespace app\modules\api\controllers;

use app\components\Jdf;
use app\models\DealEvent;
use app\models\EventSearch;
use app\models\Log;
use Yii;
use app\components\ApiComponent;
use app\models\Customer;
use app\models\CustomerSearch;
use app\models\Deal;
use app\models\DealSearch;
use app\models\Meeting;
use app\models\UserDeal;
use webvimark\modules\UserManagement\models\User;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class DealController extends \yii\rest\Controller
{

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

    public function actionGetDeal() {
        $request = ApiComponent::parseInputData();

        if (isset($request['id'])) {
            $deals = (new Query())
                ->select(['deal.subject', 'deal.level', 'deal.created_at',
                    'event.id as event_id', 'event.name as event_name',
                    'deal_event.id as deal_event_id', 'deal_event.value as event_date', 'deal_event.business_day'])
                ->from('deal')
                ->leftJoin('deal_event', 'deal_event.deal_id = deal.id')
                ->leftJoin('event', 'event.id = deal_event.event_id')
                ->where('deal.id=' . $request['id'])
                ->orderBy('event.id')
                ->all();

            if($deals) {

                $data = [
                    'subject' => $deals[0]['subject'],
                    'level' => $deals[0]['level'],
                    'created_at' => Jdf::jdate('Y/m/d H:i', $deals[0]['created_at']),
                ];

                $deal_events = [];
                foreach ($deals as $deal) {
                    if($deal['event_name']) {
                        $deal_events[] = [
                            "deal_event_id" => $deal['deal_event_id'],
                            "event_id" => $deal['event_id'],
                            "event_name" => $deal['event_name'],
                            "event_date" => Jdf::jdate('F Y', $deal['event_date']),
                            "event_full_date" => $deal['event_date'],
                            "business_day" => $deal['business_day']
                        ];
                    }
                }
                $data['events'] = $deal_events;

                return ApiComponent::successResponse('Return requested deal info',
                    $data, true);
            } else {
                return ApiComponent::errorResponse([], 1002);

            }

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    public function actionNew()
    {
        $request = ApiComponent::parseInputData();

        if (isset($request['subject']) && isset($request['price']) && isset($request['customer_id'])) {

            $model = new Deal();
            $model->subject = $request['subject'];
            $model->price = $request['price'];
            $model->customer_id = $request['customer_id'];
            $model->created_at = time();
            $model->level = 0;
            if ($model->save()) {
                
                return ApiComponent::successResponse('Deal saved successfully', $model, true);
            } else {
                return ApiComponent::errorResponse([], 500);
            }

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    public function actionEdit()
    {
        $request = ApiComponent::parseInputData();

        if (isset($request['id'])) {

            $model = Deal::find()->where('id='.$request['id'])->one();
            if($model) {
                
                if(isset($request['subject'])){
                    $model->subject = $request['subject'];
                }

                if(isset($request['price'])){
                    $model->price = $request['price'];
                }
                $model->save();

                return ApiComponent::successResponse('Deal updated successfully', $model, true);
            } else {
                return ApiComponent::errorResponse([], 1002);
            }

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    public function actionAddDealEvent() {
        $request = ApiComponent::parseInputData();

        if (isset($request['event_id']) && isset($request['date'])
            && isset($request['deal_id']) && isset($request['business_day'])) {

            $deal = Deal::find()->where('id=' . $request['deal_id'])->one();

            if($deal) {

                $newDealEvent = new DealEvent();
                $newDealEvent->deal_id = $request['deal_id'];
                $newDealEvent->event_id = $request['event_id'];
                $newDealEvent->value = $request['date'];
                $newDealEvent->user_id = Yii::$app->user->id;
                $newDealEvent->business_day = $request['business_day'];
                $newDealEvent->save();

                return ApiComponent::successResponse('Event added to Deal successfully', $newDealEvent, false);
            } else {
                return ApiComponent::errorResponse([], 1002);
            }

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    public function actionEditDealEvent() {
        $request = ApiComponent::parseInputData();

        if (isset($request['deal_event_id']) && isset($request['event_id']) && isset($request['date']) && isset($request['business_day'])) {

            $dealEvent = DealEvent::find()->where('id=' . $request['deal_event_id'])->one();

            if($dealEvent) {

                DealEvent::updateAll([
                    'event_id' => $request['event_id'],
                    'value' => $request['date'],
                    'business_day' => $request['business_day'],

                ],['id' => $request['deal_event_id']]);

                return ApiComponent::successResponse('Deal Event updated successfully', $dealEvent, false);
            } else {
                return ApiComponent::errorResponse([], 1002);
            }

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    public function actionDeleteDealEvent() {
        $request = ApiComponent::parseInputData();

        if (isset($request['deal_event_id'])) {
            $dealEvent = DealEvent::find()->where('id='.$request['deal_event_id'])->one();

            if($dealEvent) {
                $dealEvent->delete();
                return ApiComponent::successResponse('Event deleted from deal successfully', [
                    $dealEvent,
                ], true);
            } else {
                return ApiComponent::errorResponse([], 1002);

            }

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    /**
     * @api {post} /deal/customer-deals 6- list of customer deals
     * @apiName 6.List of customer deals
     * @apiGroup Deal
     *
     * @apiParam {String} customer_id customer id.
     *
     * @apiParamExample {json} Request-Example:
     *      {
     *         "customer_id":"5"
     *      }
     *
     *
     * @apiSuccess {Array} data response data.
     * @apiSuccess {String} data.id deal id.
     * @apiSuccess {String} data.customer_id customer id.
     * @apiSuccess {String} data.subject deal subject.
     * @apiSuccess {String} data.price deal price.
     * @apiSuccess {String} data.level deal level.
     * @apiSuccess {String} data.created_at deal submit date.
     * @apiSuccess {String} data.customerName deal customer code.
     * @apiSuccess {String} data.firstName customer first name.
     * @apiSuccess {String} data.lastName customer last name.
     * @apiSuccess {String} data.mobile customer mobile.
     * @apiSuccess {String} data.levelName deal level name.
     * @apiSuccess {String} data.sum_rating sum of ratings of deal meetings.
     * @apiSuccess {String} data.latestMeeting deal last meeting date [timestamp].
     * @apiSuccess {String} data.nextMeeting deal next meeting date.
     * @apiSuccess {String} data.meetingCount deal meetings count.
     * @apiSuccess {String} message response message.
     * @apiSuccess {Integer} code response code [0: failure, 1: success].
     * @apiSuccess {Integer} status response status code [see -status table-].
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       {
     *           "data": [
     *              {
     *                    "id": "2",
     *                    "customer_id": "5",
     *                    "subject": "پفک نمکی",
     *                    "price": "12000000",
     *                    "level": "3",
     *                    "created_at": "1531251000",
     *                    "updated_at": null,
     *                    "customerName": "5",
     *                    "firstName": "علی",
     *                    "lastName": "محمدی",
     *                    "mobile": "3829749",
     *                    "levelName": "پیش نویس",
     *                    "sum_rating": "12",
     *                    "latestMeeting": "1532633400",
     *                    "nextMeeting": "1537471800",
     *                    "meetingCount": "3"
     *              },
     *              {
     *                    "id": "3",
     *                    "customer_id": "5",
     *                    "subject": "پشتیبانی",
     *                    "price": "65222200",
     *                    "level": "2",
     *                    "created_at": "1531596600",
     *                    "updated_at": null,
     *                    "customerName": "5",
     *                    "firstName": "علی",
     *                    "lastName": "محمدی",
     *                    "mobile": "3829749",
     *                    "levelName": "پیش پرداخت",
     *                    "sum_rating": "7",
     *                    "latestMeeting": "1531251000",
     *                    "nextMeeting": "1532028600",
     *                    "meetingCount": "2"
     *              }
     *           ],
     *           "message": "",
     *           "code": 1,
     *           "status": 200
     *       }
     *
     *
     * @apiError EnterRequiredInputs
     * @apiErrorExample Error-Response 1000:
     *     HTTP/1.1 400 Bad request
     *     {
     *         "data": [],
     *         "message": "Enter required data",
     *         "code": 0,
     *         "status": 1000
     *     }
     */
    public function actionCustomerDeals()
    {
        $request = ApiComponent::parseInputData();
        $customerId = 0;
        $user_deals_id = [];
        $user_deals_id[] = -1;

        if (isset($request['id'])) {
            $user_deals = Deal::find()->select('id')->where('customer_id=' . $request['id'])->all();

            foreach ($user_deals as $user_deal) {
                $user_deals_id[] = $user_deal->id;
            }
        } else {
            $user_deals = UserDeal::find()->select('deal_id')->where('user_id=' . Yii::$app->user->id)->all();

            foreach ($user_deals as $user_deal) {
                $user_deals_id[] = $user_deal->deal_id;
            }
        }

        $searchModel = new DealSearch();
        $dataProvider = $searchModel->searchUserDeals(Yii::$app->request->queryParams, $user_deals_id, true);

        $deals_data = $dataProvider->getModels();
        $index = 0;
        $data = [];
        foreach ($deals_data as $deal) {
            $deal['tasks'] = \app\models\Task::getDealTasksStatus($deal['id']);
            $data[] = $deal;
        }

        $page = $dataProvider->pagination->page + 1;
        $page_size = $dataProvider->pagination->pageSize;
        $pages = ceil($dataProvider->getTotalCount() / $page_size);

        return ApiComponent::successResponse('deals list', [
            'data' => $data,
            'page' => $page,
            'page_size' => $page_size,
            'pages' => $pages
        ], true);

    }

    /**
     * @api {post} /deal/all-deals 7- list of all deals
     * @apiName 7.List of all deals
     * @apiGroup Deal
     *
     *
     * @apiSuccess {Array} data response data.
     * @apiSuccess {String} data.id deal id.
     * @apiSuccess {String} data.customer_id customer id.
     * @apiSuccess {String} data.subject deal subject.
     * @apiSuccess {String} data.price deal price.
     * @apiSuccess {String} data.level deal level.
     * @apiSuccess {String} data.created_at deal submit date.
     * @apiSuccess {String} data.customerName deal customer code.
     * @apiSuccess {String} data.firstName customer first name.
     * @apiSuccess {String} data.lastName customer last name.
     * @apiSuccess {String} data.mobile customer mobile.
     * @apiSuccess {String} data.levelName deal level name.
     * @apiSuccess {String} data.sum_rating sum of ratings of deal meetings.
     * @apiSuccess {String} data.latestMeeting deal last meeting date [timestamp].
     * @apiSuccess {String} data.nextMeeting deal next meeting date.
     * @apiSuccess {String} data.meetingCount deal meetings count.
     * @apiSuccess {String} message response message.
     * @apiSuccess {Integer} code response code [0: failure, 1: success].
     * @apiSuccess {Integer} status response status code [see -status table-].
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       {
     *           "data": [
     *              {
     *                    "id": "2",
     *                    "customer_id": "5",
     *                    "subject": "پفک نمکی",
     *                    "price": "12000000",
     *                    "level": "3",
     *                    "created_at": "1531251000",
     *                    "updated_at": null,
     *                    "customerName": "5",
     *                    "firstName": "علی",
     *                    "lastName": "محمدی",
     *                    "mobile": "3829749",
     *                    "levelName": "پیش نویس",
     *                    "sum_rating": "12",
     *                    "latestMeeting": "1532633400",
     *                    "nextMeeting": "1537471800",
     *                    "meetingCount": "3"
     *              },
     *              {
     *                    "id": "3",
     *                    "customer_id": "5",
     *                    "subject": "پشتیبانی",
     *                    "price": "65222200",
     *                    "level": "2",
     *                    "created_at": "1531596600",
     *                    "updated_at": null,
     *                    "customerName": "5",
     *                    "firstName": "علی",
     *                    "lastName": "محمدی",
     *                    "mobile": "3829749",
     *                    "levelName": "پیش پرداخت",
     *                    "sum_rating": "7",
     *                    "latestMeeting": "1531251000",
     *                    "nextMeeting": "1532028600",
     *                    "meetingCount": "2"
     *              }
     *           ],
     *           "message": "",
     *           "code": 1,
     *           "status": 200
     *       }
     *
     *
     */
    public function actionAdminDeals()
    {
        $searchModel = new DealSearch();
        $dataProvider = $searchModel->searchAll(\Yii::$app->request->queryParams, false, true);

        $data = $dataProvider->getModels();
        $index = 0;
        foreach ($data as $deal) {
            $deal['tasks'] = \app\models\Task::getDealTasksStatus($deal['id']);
            $data[$index++] = $deal;
        }

        $page = $dataProvider->pagination->page + 1;
        $page_size = $dataProvider->pagination->pageSize;
        $pages = ceil($dataProvider->getTotalCount() / $page_size);

        return ApiComponent::successResponse('deals list', [
            'data' => $data,
            'page' => $page,
            'page_size' => $page_size,
            'pages' => $pages
        ], true);
    }

    ////////////////////////  Customers in deals ///////////////////////////
    public function actionCustomersInDeals() {

        $deals = \yii\helpers\ArrayHelper::map(Deal::find()->all(), 'id', 'subject');

        $users = \app\models\User::findUsersByRole('customer');
        $users = \yii\helpers\ArrayHelper::map($users, 'id', 'username');

        $query = (new Query())
            ->select(['user_deal.id', 'user.username', 'deal.subject', 'user_deal.created_at'])
            ->from('user_deal')
            ->leftJoin('user', 'user.id=user_deal.user_id')
            ->leftJoin('deal', 'deal.id=user_deal.deal_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $data = $dataProvider->getModels();
        $index = 0;
        foreach ($data as $userDeal) {
            $userDeal['created_at'] = Jdf::jdate('Y/m/d H:i', $userDeal['created_at']);
            $data[$index++] = $userDeal;
        }
        $page = $dataProvider->pagination->page + 1;
        $page_size = $dataProvider->pagination->pageSize;
        $pages = ceil($dataProvider->getTotalCount() / $page_size);

        $list = [
            'data' => $data,
            'page' => $page,
            'page_size' => $page_size,
            'pages' => $pages
        ];

        $resultData = [
            'customerDeals' => $list,
            'deals' => $deals,
            'customers' => $users,
        ];

        return ApiComponent::successResponse('Customer deals list', [
            'data' => $resultData,
        ], true);
    }

    public function actionAddCustomerToDeal() {
        $request = ApiComponent::parseInputData();

        if (isset($request['customer_id']) && isset($request['deal_id'])) {
            $model = new UserDeal();
            $model->user_id = $request['customer_id'];
            $model->deal_id = $request['deal_id'];
            $model->created_at = time();
            $model->save();

            Log::addLog(Log::AddUserToDeal, $model->user_id . '-' . $model->deal_id);

            return ApiComponent::successResponse('Customer added to Deal successfully', [
                $model,
            ], true);

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    //remove customer from deal
    public function actionDeleteCustomerFromDeal() {
        $request = ApiComponent::parseInputData();

        if (isset($request['id'])) {
            $customerDeal = UserDeal::find()->where('id='.$request['id'])->one();

            if($customerDeal) {
                $customerDeal->delete();
                return ApiComponent::successResponse('customer deleted from Deal successfully', [
                    $customerDeal,
                ], true);
            } else {
                return ApiComponent::errorResponse([], 1002);

            }

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }
    ////////////////////////////////////////////////////////////////////////
}