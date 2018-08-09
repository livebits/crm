<?php

namespace app\modules\api\controllers;

use app\components\ApiComponent;
use app\models\DealLevelSearch;
use app\models\DealSearch;
use app\models\Media;
use app\models\Meeting;
use app\models\MeetingSearch;
use yii\data\ArrayDataProvider;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class MeetingController extends \yii\rest\Controller
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
     * @api {post} /meeting/get-customer-meetings 12- List of customer meetings
     * @apiName 12.List of customer meetings
     * @apiGroup Meeting
     *
     * @apiParam {String} customer_id customer id.
     *
     * @apiParamExample {json} Request-Example:
     *      {
     *         "customer_id":"5"
     *      }
     *
     * @apiSuccess {Array} data response data.
     * @apiSuccess {String} data.username user name.
     * @apiSuccess {String} data.id meeting id.
     * @apiSuccess {String} data.user_id user id.
     * @apiSuccess {String} data.customer_id customer id.
     * @apiSuccess {String} data.content meeting content.
     * @apiSuccess {String} data.created_at meeting date [timestamp].
     * @apiSuccess {String} data.next_date meeting next date [timestamp].
     * @apiSuccess {String} data.rating meeting rate.
     * @apiSuccess {String} data.imagesCount number of meeting image files.
     * @apiSuccess {String} data.audiosCount number of meeting audio files.
     * @apiSuccess {String} data.attachsCount number of meeting other attachment files.
     * @apiSuccess {String} message response message.
     * @apiSuccess {Integer} code response code [0: failure, 1: success].
     * @apiSuccess {Integer} status response status code [see -status table-].
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       {
     *      "data": [
     *             {
     *                "username": "superadmin",
     *                "id": "44",
     *                "user_id": "1",
     *                "customer_id": "5",
     *                "content": "my content....",
     *                "created_at": "1532633400",
     *                "next_date": "1537299000",
     *                "rating": "4",
     *                "imagesCount": "0",
     *                "audiosCount": "0",
     *                "attachsCount": "0"
     *            },
     *            {
     *                "username": "superadmin",
     *                "id": "42",
     *                "user_id": "1",
     *                "customer_id": "5",
     *                "content": "oooops",
     *                "created_at": "1532633400",
     *                "next_date": "1536607800",
     *                "rating": "4",
     *                "imagesCount": "0",
     *                "audiosCount": "5",
     *                "attachsCount": "4"
     *            }
     *      ],
     *      "message": "",
     *      "code": 1,
     *      "status": 200
     *   }
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
     *
     */
    public function actionGetCustomerMeetings()
    {
        $request = ApiComponent::parseInputData();

        if (isset($request['customer_id'])) {

            $searchModel = new MeetingSearch();
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
     * @api {post} /meeting/get-deal-meetings 13- List of deal meetings
     * @apiName 13.List of deal meetings
     * @apiGroup Meeting
     *
     * @apiParam {String} deal_id deal id.
     *
     * @apiParamExample {json} Request-Example:
     *      {
     *         "deal_id":"2"
     *      }
     *
     * @apiSuccess {Array} data response data.
     * @apiSuccess {String} data.username user name.
     * @apiSuccess {String} data.id meeting id.
     * @apiSuccess {String} data.user_id user id.
     * @apiSuccess {String} data.deal_id deal id.
     * @apiSuccess {String} data.content meeting content.
     * @apiSuccess {String} data.created_at meeting date [timestamp].
     * @apiSuccess {String} data.next_date meeting next date [timestamp].
     * @apiSuccess {String} data.rating meeting rate.
     * @apiSuccess {String} data.imagesCount number of meeting image files.
     * @apiSuccess {String} data.audiosCount number of meeting audio files.
     * @apiSuccess {String} data.attachsCount number of meeting other attachment files.
     * @apiSuccess {String} message response message.
     * @apiSuccess {Integer} code response code [0: failure, 1: success].
     * @apiSuccess {Integer} status response status code [see -status table-].
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       {
     *      "data": [
     *             {
     *                "username": "superadmin",
     *                "id": "44",
     *                "user_id": "1",
     *                "deal_id": "2",
     *                "content": "texttttt",
     *                "created_at": "1532633400",
     *                "next_date": "1537299000",
     *                "rating": "4",
     *                "imagesCount": "0",
     *                "audiosCount": "0",
     *                "attachsCount": "0"
     *            },
     *            {
     *                "username": "superadmin",
     *                "id": "42",
     *                "user_id": "1",
     *                "deal_id": "2",
     *                "content": "www.www.www",
     *                "created_at": "1532633400",
     *                "next_date": "1536607800",
     *                "rating": "4",
     *                "imagesCount": "0",
     *                "audiosCount": "5",
     *                "attachsCount": "4"
     *            }
     *      ],
     *      "message": "",
     *      "code": 1,
     *      "status": 200
     *   }
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
     *
     */
    public function actionGetDealMeetings()
    {
        $request = ApiComponent::parseInputData();

        if (isset($request['deal_id'])) {

            $searchModel = new MeetingSearch();
            $query = $searchModel->searchForDeals(\Yii::$app->request->queryParams, $request['deal_id'], true);

            $dataProvider = new ArrayDataProvider([
                'allModels' => $query->asArray()->all(),
            ]);

            return ApiComponent::successResponse('', $dataProvider->allModels, true);

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    /**
     * @api {post} /meeting/meeting-detail 14- get meeting detail
     * @apiName 14.get meeting detail
     * @apiGroup Meeting
     *
     * @apiParam {String} meeting_id meeting id.
     *
     * @apiParamExample {json} Request-Example:
     *      {
     *         "meeting_id":"2"
     *      }
     *
     * @apiSuccess {Array} data response data.
     * @apiSuccess {String} data.id meeting id.
     * @apiSuccess {String} data.user_id user id.
     * @apiSuccess {String} data.customer_id customer id (only for customer meetings).
     * @apiSuccess {String} data.content meeting content.
     * @apiSuccess {String} data.created_at meeting submit date [ts].
     * @apiSuccess {String} data.next_date meeting next date [timestamp].
     * @apiSuccess {String} data.rating meeting rating [1-5].
     * @apiSuccess {String} data.deal_id deal id (only for deal meetings).
     * @apiSuccess {Array} data.media meeting media attachments.
     * @apiSuccess {String} data.media.id media id.
     * @apiSuccess {String} data.media.type media type [IMAGE, AUDIO, OTHER].
     * @apiSuccess {String} data.media.filename media file name [file path: <site_url>/web/media/[audio | images | other]/<filename>].
     * @apiSuccess {String} data.media.created_at media created date.
     * @apiSuccess {String} message response message.
     * @apiSuccess {Integer} code response code [0: failure, 1: success].
     * @apiSuccess {Integer} status response status code [see -status table-].
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       {
     *          "data": [
     *               {
     *                   "id": "2",
     *                   "user_id": "1",
     *                   "customer_id": "5",
     *                   "content": "متن جلسه",
     *                   "created_at": "1530396000",
     *                   "next_date": "1531692000",
     *                   "rating": "3",
     *                   "deal_id": null,
     *                   "media": [
     *                       {
     *                           "id": 15,
     *                           "type": "IMAGE",
     *                           "filename": "15304446615b38bb75b7c586.99677907_1.jpg",
     *                           "created_at": 1530444661
     *                       }
     *                   ]
     *               }
     *           ],
     *           "message": "",
     *           "code": 1,
     *           "status": 200
     *    }
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
     *
     * @apiError ItemNotFound
     * @apiErrorExample Error-Response 1002:
     *     HTTP/1.1 400 Bad request
     *     {
     *         "data": [],
     *         "message": "Item not found",
     *         "code": 0,
     *         "status": 1002
     *     }
     *
     */
    public function actionMeetingDetail() {

        $request = ApiComponent::parseInputData();

        if (isset($request['meeting_id'])) {

            $meeting = Meeting::find()->where('id=' . $request['meeting_id'])->asArray()->one();

            if($meeting) {
                $media = Media::find()
                    ->select(['id', 'type', 'filename', 'created_at'])
                    ->where('meeting_id=' . $request['meeting_id'])
                    ->all();

                $meeting['media'] = $media;

                return ApiComponent::successResponse('', $meeting);

            } else {
                return ApiComponent::errorResponse([], 1002);
            }

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

}