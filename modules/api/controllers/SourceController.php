<?php

namespace app\modules\api\controllers;

use Yii;
use app\components\ApiComponent;
use app\models\SourceSearch;
use app\models\Source;
use yii\data\ArrayDataProvider;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
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
                QueryParamAuth::className(),
            ],
        ];

        return $behaviors;
    }

    /////////////////////////////     Sources     ///////////////////////////
    public function actionGetSources() {
        $searchModel = new SourceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $data = $dataProvider->getModels();
        $page = $dataProvider->pagination->page + 1;
        $page_size = $dataProvider->pagination->pageSize;
        $pages = ceil($dataProvider->getTotalCount() / $page_size);

        return ApiComponent::successResponse('Source list', [
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

            $model = new Source();
            $model->name = $request['name'];
            if ($model->save()) {
                return ApiComponent::successResponse('Source saved successfully', $model, true);
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

            $model = Source::find()->where('id='.$request['id'])->one();
            if($model) {
                Source::updateAll(['name' => $request['name']], ['id' => $request['id']]);

                return ApiComponent::successResponse('Source updated successfully', $model, true);
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

            $model = Source::find()->where('id='.$request['id'])->one();
            if($model) {
                Source::deleteAll('id='. $request['id']);
                return ApiComponent::successResponse('Source deleted successfully', $model, true);
            } else {
                return ApiComponent::errorResponse([], 1002);
            }

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }
    ////////////////////////////////////////////////////////////////////////

}