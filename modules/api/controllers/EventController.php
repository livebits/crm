<?php

namespace app\modules\api\controllers;

use app\components\Jdf;
use app\models\Event;
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

class EventController extends \yii\rest\Controller
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

    /////////////////////////////     Events     ///////////////////////////
    public function actionGetEvents() {
        $searchModel = new EventSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $data = $dataProvider->getModels();
        $index = 0;
        foreach ($data as $event) {
            $event['created_at'] = Jdf::jdate('Y/m/d H:i', $event['created_at']);
            $data[$index++] = $event;
        }

        $page = $dataProvider->pagination->page + 1;
        $page_size = $dataProvider->pagination->pageSize;
        $pages = ceil($dataProvider->getTotalCount() / $page_size);

        return ApiComponent::successResponse('Deal events list', [
            'data' => $data,
            'page' => $page,
            'page_size' => $page_size,
            'pages' => $pages
        ], true);
    }

    public function actionNew()
    {
        $request = ApiComponent::parseInputData();

        if (isset($request['name'])) {

            $model = new Event();
            $model->name = $request['name'];
//            $model->priority = $request['priority'];
            if ($model->save()) {
                return ApiComponent::successResponse('Event saved successfully', $model, true);
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

        if (isset($request['id']) && isset($request['name'])) {

            $model = Event::find()->where('id='.$request['id'])->one();
            if($model) {
                Event::updateAll(['name' => $request['name'], 'priority' => $request['priority']], ['id' => $request['id']]);
                return ApiComponent::successResponse('Event updated successfully', $model, true);
            } else {
                return ApiComponent::errorResponse([], 1002);
            }

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    public function actionDelete()
    {
        $request = ApiComponent::parseInputData();

        if (isset($request['id'])) {

            $model = Event::find()->where('id='.$request['id'])->one();
            if($model) {
                Event::deleteAll('id='. $request['id']);
                return ApiComponent::successResponse('Event deleted successfully', $model, true);
            } else {
                return ApiComponent::errorResponse([], 1002);
            }

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }
    ////////////////////////////////////////////////////////////////////////
}