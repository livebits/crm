<?php

namespace app\modules\api\controllers;

use app\components\ApiComponent;
use app\models\DealLevelSearch;
use app\models\DealSearch;
use yii\data\ArrayDataProvider;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class DealLevelController extends \yii\rest\Controller
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

    /**
     * @api {post} /deal-level/get-levels 9- list of all deal levels
     * @apiName 9.List of all deal levels
     * @apiGroup Deal
     *
     *
     * @apiSuccess {Array} data response data.
     * @apiSuccess {String} data.id deal level id.
     * @apiSuccess {String} data.level_number level number.
     * @apiSuccess {String} data.level_name level name.
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
     *                   "id": 3,
     *                   "level_number": 1,
     *                   "level_name": "پیش نویس",
     *               },
     *               {
     *                   "id": 2,
     *                   "level_number": 2,
     *                   "level_name": "پیش پرداخت",
     *               }
     *           ],
     *           "message": "",
     *           "code": 1,
     *           "status": 200
     *       }
     *
     */
    public function actionGetLevels()
    {
        $searchModel = new DealLevelSearch();
        $query = $searchModel->search(\Yii::$app->request->queryParams, true);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->all(),
        ]);

        return ApiComponent::successResponse('', $dataProvider->allModels, true);
    }

}