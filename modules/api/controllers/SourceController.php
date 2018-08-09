<?php

namespace app\modules\api\controllers;

use app\components\ApiComponent;
use app\models\SourceSearch;
use yii\data\ArrayDataProvider;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class SourceController extends \yii\rest\Controller
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
     * @api {post} /deal-level/get-levels 11- list of all deal levels
     * @apiName 11.List of all deal levels
     * @apiGroup Deal
     *
     *
     * @apiSuccess {Array} data response data.
     * @apiSuccess {String} data.id source id.
     * @apiSuccess {String} data.name source name.
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
     *                   "id": 1,
     *                   "name": "آقای سعیدی"
     *               },
     *               {
     *                   "id": 2,
     *                   "name": "روزنامه های کثیر الانتشار"
     *               }
     *           ],
     *           "message": "",
     *           "code": 1,
     *           "status": 200
     *      }
     *
     */
    public function actionGetSources()
    {
        $searchModel = new SourceSearch();
        $query = $searchModel->search(\Yii::$app->request->queryParams, true);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->all(),
        ]);

        return ApiComponent::successResponse('', $dataProvider->allModels, true);
    }

}