<?php

namespace app\modules\api\controllers;

use app\components\ApiComponent;
use app\models\Customer;
use app\models\CustomerSearch;
use app\models\Deal;
use app\models\Meeting;
use webvimark\modules\UserManagement\models\User;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class CustomerController extends \yii\rest\Controller
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
     * @api {post} /customer/clues 2- List of clues
     * @apiName 2.List of Clues
     * @apiGroup Customer
     *
     *
     *
     * @apiSuccess {Array} data response data.
     * @apiSuccess {String} data.id customer id.
     * @apiSuccess {String} data.user_id customer creator user id.
     * @apiSuccess {String} data.firstName customer first name.
     * @apiSuccess {String} data.lastName customer last name.
     * @apiSuccess {String} data.companyName customer company.
     * @apiSuccess {String} data.position customer position.
     * @apiSuccess {String} data.mobile customer mobile.
     * @apiSuccess {String} data.phone customer phone number.
     * @apiSuccess {String} data.source customer source.
     * @apiSuccess {String} data.description extra desc about customer.
     * @apiSuccess {String} data.status customer status [0:clue, 1:customer, 3:off-customer].
     * @apiSuccess {String} data.created_at customer creation date.
     * @apiSuccess {String} data.image customer image file name [full path= <site_url>/web/Uploads/<file_name>].
     * @apiSuccess {String} data.sum_rating sum rating of all customer meetings.
     * @apiSuccess {String} data.latestMeeting customer last meeting date [timestamp].
     * @apiSuccess {String} data.nextMeeting customer next meeting date.
     * @apiSuccess {String} data.meetingCount customer meetings count.
     * @apiSuccess {String} message response message.
     * @apiSuccess {Integer} code response code [0: failure, 1: success].
     * @apiSuccess {Integer} status response status code [see -status table-].
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       {
     *      "data": [
     *          {
     *              "id": "5",
     *              "user_id": "1",
     *              "firstName": "علی",
     *              "lastName": "محمدی",
     *              "companyName": "مایکروسافت",
     *              "position": "مدیر عامل",
     *              "mobile": "3829749",
     *              "phone": "5345645",
     *              "source": "1",
     *              "description": "این قسمت برای وارد کردن توضیحات می باشد",
     *              "status": "0",
     *              "created_at": "1530598132",
     *              "updated_at": "1531222225",
     *              "image": "image1531222225photo_2018-03-14_11-04-57.jpg",
     *              "sum_rating": "35",
     *              "latestMeeting": "1532633400",
     *              "nextMeeting": "1537299000",
     *              "meetingCount": "10"
     *          },
     *          {
     *               "id": "10",
     *               "user_id": "2",
     *               "firstName": "new",
     *               "lastName": "clue",
     *               "companyName": "com",
     *               "position": "manager",
     *               "mobile": "34343",
     *               "phone": "",
     *               "source": "1",
     *               "description": "",
     *               "status": "0",
     *               "created_at": "1532432251",
     *               "updated_at": "1532432256",
     *               "image": "",
     *               "sum_rating": "3",
     *               "latestMeeting": "1532287800",
     *               "nextMeeting": "1532287800",
     *               "meetingCount": "1"
     *          }
     *      ],
     *      "message": "",
     *      "code": 1,
     *      "status": 200
     *   }
     *
     */
    public function actionClues()
    {
        $searchModel = new CustomerSearch();
        $query = $searchModel->searchClues(\Yii::$app->request->queryParams, true);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->asArray()->all(),
        ]);

        return ApiComponent::successResponse('', $dataProvider->allModels, true);
    }

    /**
     * @api {post} /customer/customers 3- List of customers
     * @apiName 3.List of customers
     * @apiGroup Customer
     *
     *
     *
     * @apiSuccess {Array} data response data.
     * @apiSuccess {String} data.id customer id.
     * @apiSuccess {String} data.user_id customer creator user id.
     * @apiSuccess {String} data.firstName customer first name.
     * @apiSuccess {String} data.lastName customer last name.
     * @apiSuccess {String} data.companyName customer company.
     * @apiSuccess {String} data.position customer position.
     * @apiSuccess {String} data.mobile customer mobile.
     * @apiSuccess {String} data.phone customer phone number.
     * @apiSuccess {String} data.source customer source.
     * @apiSuccess {String} data.description extra desc about customer.
     * @apiSuccess {String} data.status customer status [0:clue, 1:customer, 3:off-customer].
     * @apiSuccess {String} data.created_at customer creation date.
     * @apiSuccess {String} data.image customer image file name [full path= <site_url>/web/Uploads/<file_name>].
     * @apiSuccess {String} data.sum_rating sum rating of all customer meetings.
     * @apiSuccess {String} data.latestMeeting customer last meeting date [timestamp].
     * @apiSuccess {String} data.nextMeeting customer next meeting date.
     * @apiSuccess {String} data.meetingCount customer meetings count.
     * @apiSuccess {String} message response message.
     * @apiSuccess {Integer} code response code [0: failure, 1: success].
     * @apiSuccess {Integer} status response status code [see -status table-].
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       {
     *      "data": [
     *          {
     *              "id": "5",
     *              "user_id": "1",
     *              "firstName": "علی",
     *              "lastName": "محمدی",
     *              "companyName": "مایکروسافت",
     *              "position": "مدیر عامل",
     *              "mobile": "3829749",
     *              "phone": "5345645",
     *              "source": "1",
     *              "description": "این قسمت برای وارد کردن توضیحات می باشد",
     *              "status": "1",
     *              "created_at": "1530598132",
     *              "updated_at": "1531222225",
     *              "image": "image1531222225photo_2018-03-14_11-04-57.jpg",
     *              "sum_rating": "35",
     *              "latestMeeting": "1532633400",
     *              "nextMeeting": "1537299000",
     *              "meetingCount": "10"
     *          },
     *          {
     *               "id": "10",
     *               "user_id": "2",
     *               "firstName": "new",
     *               "lastName": "clue",
     *               "companyName": "com",
     *               "position": "manager",
     *               "mobile": "34343",
     *               "phone": "",
     *               "source": "1",
     *               "description": "",
     *               "status": "1",
     *               "created_at": "1532432251",
     *               "updated_at": "1532432256",
     *               "image": "",
     *               "sum_rating": "3",
     *               "latestMeeting": "1532287800",
     *               "nextMeeting": "1532287800",
     *               "meetingCount": "1"
     *          }
     *      ],
     *      "message": "",
     *      "code": 1,
     *      "status": 200
     *   }
     *
     */
    public function actionCustomers()
    {
        $searchModel = new CustomerSearch();
        $query = $searchModel->searchCustomers(\Yii::$app->request->queryParams, true);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->asArray()->all(),
        ]);

        return ApiComponent::successResponse('', $dataProvider->allModels, true);
    }

    /**
     * @api {post} /customer/off-customers 4- List of off-customers
     * @apiName 4.List of off-customers
     * @apiGroup Customer
     *
     *
     *
     * @apiSuccess {Array} data response data.
     * @apiSuccess {String} data.id customer id.
     * @apiSuccess {String} data.user_id customer creator user id.
     * @apiSuccess {String} data.firstName customer first name.
     * @apiSuccess {String} data.lastName customer last name.
     * @apiSuccess {String} data.companyName customer company.
     * @apiSuccess {String} data.position customer position.
     * @apiSuccess {String} data.mobile customer mobile.
     * @apiSuccess {String} data.phone customer phone number.
     * @apiSuccess {String} data.source customer source.
     * @apiSuccess {String} data.description extra desc about customer.
     * @apiSuccess {String} data.status customer status [0:clue, 1:customer, 3:off-customer].
     * @apiSuccess {String} data.created_at customer creation date.
     * @apiSuccess {String} data.image customer image file name [full path= <site_url>/web/Uploads/<file_name>].
     * @apiSuccess {String} data.sum_rating sum rating of all customer meetings.
     * @apiSuccess {String} data.latestMeeting customer last meeting date [timestamp].
     * @apiSuccess {String} data.nextMeeting customer next meeting date.
     * @apiSuccess {String} data.meetingCount customer meetings count.
     * @apiSuccess {String} message response message.
     * @apiSuccess {Integer} code response code [0: failure, 1: success].
     * @apiSuccess {Integer} status response status code [see -status table-].
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       {
     *      "data": [
     *          {
     *              "id": "5",
     *              "user_id": "1",
     *              "firstName": "علی",
     *              "lastName": "محمدی",
     *              "companyName": "مایکروسافت",
     *              "position": "مدیر عامل",
     *              "mobile": "3829749",
     *              "phone": "5345645",
     *              "source": "1",
     *              "description": "این قسمت برای وارد کردن توضیحات می باشد",
     *              "status": "3",
     *              "created_at": "1530598132",
     *              "updated_at": "1531222225",
     *              "image": "image1531222225photo_2018-03-14_11-04-57.jpg",
     *              "sum_rating": "35",
     *              "latestMeeting": "1532633400",
     *              "nextMeeting": "1537299000",
     *              "meetingCount": "10"
     *          },
     *          {
     *               "id": "10",
     *               "user_id": "2",
     *               "firstName": "new",
     *               "lastName": "clue",
     *               "companyName": "com",
     *               "position": "manager",
     *               "mobile": "34343",
     *               "phone": "",
     *               "source": "1",
     *               "description": "",
     *               "status": "3",
     *               "created_at": "1532432251",
     *               "updated_at": "1532432256",
     *               "image": "",
     *               "sum_rating": "3",
     *               "latestMeeting": "1532287800",
     *               "nextMeeting": "1532287800",
     *               "meetingCount": "1"
     *          }
     *      ],
     *      "message": "",
     *      "code": 1,
     *      "status": 200
     *   }
     *
     */
    public function actionOffCustomers()
    {
        $searchModel = new CustomerSearch();
        $query = $searchModel->searchOffCustomers(\Yii::$app->request->queryParams, true);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->asArray()->all(),
        ]);

        return ApiComponent::successResponse('', $dataProvider->allModels, true);
    }

    /**
     * @api {post} /customer/contacts 5- List of contacts
     * @apiName 5.List of contacts
     * @apiGroup Customer
     *
     *
     *
     * @apiSuccess {Array} data response data.
     * @apiSuccess {String} data.id customer id.
     * @apiSuccess {String} data.user_id customer creator user id.
     * @apiSuccess {String} data.firstName customer first name.
     * @apiSuccess {String} data.lastName customer last name.
     * @apiSuccess {String} data.companyName customer company.
     * @apiSuccess {String} data.position customer position.
     * @apiSuccess {String} data.mobile customer mobile.
     * @apiSuccess {String} data.phone customer phone number.
     * @apiSuccess {String} data.source customer source.
     * @apiSuccess {String} data.description extra desc about customer.
     * @apiSuccess {String} data.status customer status [0:clue, 1:customer, 3:off-customer].
     * @apiSuccess {String} data.created_at customer creation date.
     * @apiSuccess {String} data.image customer image file name [full path= <site_url>/web/Uploads/<file_name>].
     * @apiSuccess {String} data.sum_rating sum rating of all customer meetings.
     * @apiSuccess {String} data.latestMeeting customer last meeting date [timestamp].
     * @apiSuccess {String} data.nextMeeting customer next meeting date.
     * @apiSuccess {String} data.meetingCount customer meetings count.
     * @apiSuccess {String} message response message.
     * @apiSuccess {Integer} code response code [0: failure, 1: success].
     * @apiSuccess {Integer} status response status code [see -status table-].
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       {
     *      "data": [
     *          {
     *               "id": "10",
     *               "user_id": "2",
     *               "firstName": "new",
     *               "lastName": "clue",
     *               "companyName": "ttrew",
     *               "position": "manager",
     *               "mobile": "34343",
     *               "phone": "",
     *               "source": "1",
     *               "description": "",
     *               "status": "1",
     *               "created_at": "1532432251",
     *               "updated_at": "1532432256",
     *               "image": "",
     *               "sum_rating": "3",
     *               "latestMeeting": "1532287800",
     *               "nextMeeting": "1532287800",
     *               "meetingCount": "1"
     *           },
     *           {
     *               "id": "11",
     *               "user_id": "9",
     *               "firstName": "علی",
     *               "lastName": "وکیلی",
     *               "companyName": "یزد",
     *               "position": "مدیر",
     *               "mobile": "54654654",
     *               "phone": "",
     *               "source": "1",
     *               "description": "",
     *               "status": "0",
     *               "created_at": "1532435269",
     *               "updated_at": null,
     *               "image": "",
     *               "sum_rating": null,
     *               "latestMeeting": null,
     *               "nextMeeting": null,
     *               "meetingCount": "0"
     *           }
     *      ],
     *      "message": "",
     *      "code": 1,
     *      "status": 200
     *   }
     *
     */
    public function actionContacts()
    {
        $searchModel = new CustomerSearch();
        $query = $searchModel->searchContacts(\Yii::$app->request->queryParams, true);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->asArray()->all(),
        ]);

        return ApiComponent::successResponse('', $dataProvider->allModels, true);
    }

}