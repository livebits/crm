<?php

namespace app\modules\api\controllers;

use app\components\ApiComponent;
use app\models\Customer;
use app\models\CustomerSearch;
use app\models\Deal;
use app\models\DealSearch;
use app\models\Meeting;
use webvimark\modules\UserManagement\models\User;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
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
            ],
        ];

        return $behaviors;
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

        if (isset($request['customer_id'])) {
            $searchModel = new DealSearch();
            $query = $searchModel->search(\Yii::$app->request->queryParams, $request['customer_id'], true);

            $dataProvider = new ArrayDataProvider([
                'allModels' => $query->asArray()->all(),
            ]);

            return ApiComponent::successResponse('', $dataProvider->allModels, true);
        } else {
            return ApiComponent::errorResponse([], 1000);
        }
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
    public function actionAllDeals()
    {
        $searchModel = new DealSearch();
        $query = $searchModel->searchAll(\Yii::$app->request->queryParams, true);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->asArray()->all(),
        ]);

        return ApiComponent::successResponse('', $dataProvider->allModels, true);
    }

}