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
use app\models\Project;
use app\models\ProjectInfo;
use app\models\ProjectInfoSearch;
use app\models\ExpertProject;
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

class ProjectInfoController extends \yii\rest\Controller
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

    /////////////////////////////     Projects-info     ///////////////////////////
    public function actionGetProjectsInfo() {
        $searchModel = new ProjectInfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);

        $data = $dataProvider->getModels();

        $page = $dataProvider->pagination->page + 1;
        $page_size = $dataProvider->pagination->pageSize;
        $pages = ceil($dataProvider->getTotalCount() / $page_size);

        return ApiComponent::successResponse('Projects-info list', [
            'data' => $data,
            'page' => $page,
            'page_size' => $page_size,
            'pages' => $pages
        ], true);
    }

    public function actionNew()
    {
        $request = Yii::$app->request->post();

        if (isset($request['project_id']) && isset($request['publish_version'])
            && isset($request['package_name']) && isset($request['api_key']) && isset($request['key_alias_password'])) {

            $model = new ProjectInfo();
            $model->project_id = $request['project_id'];
            $model->user_id = \Yii::$app->user->id;
            $model->publish_version = $request['publish_version'];
            $model->package_name = $request['package_name'];
            $model->api_key = $request['api_key'];
            $model->key_alias_password = $request['key_alias_password'];
            $model->sign_file = isset($request['sign_file']) ? $request['sign_file'] : '';
            $model->key_alias_password = isset($request['keystore']) ? $request['keystore'] : '';
            $model->created_at = time();

            $transaction = Yii::$app->getDb()->beginTransaction();
            $dbSuccess = true;

            if (!$model->save()) {
                $dbSuccess = false;
            }

            if ($dbSuccess) {
                $prefix = "" . time();
                $sign_file_name = '';
                $keystore_file_name = '';

                if ($_FILES) {
                    if (isset($_FILES['ProjectInfo']['name']['sign_file_upload'])) {
                        $uid = uniqid(time(), true);
                        $file_name = $uid . '_' . $model->id . '_' . $_FILES['ProjectInfo']['name']['sign_file_upload'];
                        $file_tmp = $_FILES['ProjectInfo']['tmp_name']['sign_file_upload'];
                        $sign_file_name = $file_name;
                        move_uploaded_file($file_tmp, 'media/project/attachments/' . $file_name);
                    }

                    if (isset($_FILES['ProjectInfo']['name']['keystore_upload'])) {
                        $uid = uniqid(time(), true);
                        $file_name = $uid . '_' . $model->id . '_' . $_FILES['ProjectInfo']['name']['keystore_upload'];
                        $file_tmp = $_FILES['ProjectInfo']['tmp_name']['keystore_upload'];
                        $keystore_file_name = $file_name;
                        move_uploaded_file($file_tmp, 'media/project/attachments/' . $file_name);
                    }
                }

                $transaction->commit();
                
                ProjectInfo::updateAll(['sign_file' => $sign_file_name, 'keystore' => $keystore_file_name], ['id' => $model->id]);

                return ApiComponent::successResponse('Project info saved successfully', $model, true);

            } else {
                $transaction->rollBack();
                return ApiComponent::errorResponse([], 500);
            }

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    public function actionEdit()
    {
        $request = Yii::$app->request->post();

        if (isset($request['id']) && isset($request['project_id']) && isset($request['publish_version'])
            && isset($request['package_name']) && isset($request['api_key']) && isset($request['key_alias_password'])) {
        
            $model = ProjectInfo::find()->where('id=' . $request['id'])->one();
            if(!$model) {
                return ApiComponent::errorResponse([], 1002);
            }

            $transaction = Yii::$app->getDb()->beginTransaction();
            $dbSuccess = true;

            if (!ProjectInfo::updateAll(
                [
                    'project_id' => $request['project_id'],
                    'user_id' => \Yii::$app->user->id,
                    'publish_version' => $request['publish_version'],
                    'package_name' => $request['package_name'],
                    'api_key' => $request['api_key'],
                    'key_alias_password' => $request['key_alias_password'],
                    'sign_file' => isset($request['sign_file']) ? $request['sign_file'] : '',
                    'keystore' => isset($request['keystore']) ? $request['keystore'] : '',
                    'updated_at' => time(),
                ],
                [
                    'id' => $request['id']
                ])) {
                $dbSuccess = false;
            }
            // var_export($request);die();

            if ($dbSuccess) {
                $prefix = "" . time();
                $sign_file_name = '';
                $keystore_file_name = '';

                if ($_FILES) {
                    
                    if (isset($_FILES['ProjectInfo']['name']['sign_file_upload'])) {
                        $uid = uniqid(time(), true);
                        $file_name = $uid . '_' . $request['id'] . '_' . $_FILES['ProjectInfo']['name']['sign_file_upload'];
                        $file_tmp = $_FILES['ProjectInfo']['tmp_name']['sign_file_upload'];
                        $sign_file_name = $file_name;
                        move_uploaded_file($file_tmp, 'media/project/attachments/' . $file_name);
                    }

                    if (isset($_FILES['ProjectInfo']['name']['keystore_upload'])) {
                        $uid = uniqid(time(), true);
                        $file_name = $uid . '_' . $request['id'] . '_' . $_FILES['ProjectInfo']['name']['keystore_upload'];
                        $file_tmp = $_FILES['ProjectInfo']['tmp_name']['keystore_upload'];
                        $keystore_file_name = $file_name;
                        move_uploaded_file($file_tmp, 'media/project/attachments/' . $file_name);
                    }
                }

                $transaction->commit();
                
                if($sign_file_name != ''){
                    ProjectInfo::updateAll(['sign_file' => $sign_file_name], ['id' => $request['id']]);
                }

                if($keystore_file_name != ''){
                    ProjectInfo::updateAll(['keystore' => $keystore_file_name], ['id' => $request['id']]);
                }

                return ApiComponent::successResponse('Project info edited successfully', $model, true);

            } else {
                $transaction->rollBack();
                return ApiComponent::errorResponse([], 500);
            }

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    public function actionDelete()
    {
        $request = ApiComponent::parseInputData();
 
        if (isset($request['id'])) {

            $model = ProjectInfo::find()->where('id='.$request['id'])->one();
            if($model) {
                ProjectInfo::deleteAll('id='. $request['id']);
                return ApiComponent::successResponse('Project info deleted successfully', $model, true);
            } else {
                return ApiComponent::errorResponse([], 1002);
            }

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }
}