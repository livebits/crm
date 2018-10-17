<?php

namespace app\modules\api\controllers;

use app\components\Jdf;
use app\models\Department;
use app\models\DepartmentSearch;
use app\models\ExpertDepartment;
use app\models\Log;
use app\models\Media;
use app\models\Receipt;
use app\models\ReceiptSearch;
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

class DepartmentController extends \yii\rest\Controller
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

    public function actionGetDepartments()
    {
        $searchModel = new DepartmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $data = $dataProvider->getModels();
        $index = 0;
        foreach ($data as $department) {
            $department['created_at'] = Jdf::jdate('Y/m/d H:i', $department['created_at']);
            $data[$index++] = $department;
        }

        $page = $dataProvider->pagination->page + 1;
        $page_size = $dataProvider->pagination->pageSize;
        $pages = ceil($dataProvider->getTotalCount() / $page_size);

        return ApiComponent::successResponse('Departments list', [
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

            $model = new Department();
            $model->name = $request['name'];
            if ($model->save()) {
                return ApiComponent::successResponse('Department saved successfully', $model, true);
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

            $model = Department::find()->where('id='.$request['id'])->one();
            if($model) {
                Department::updateAll(['name' => $request['name']], ['id' => $request['id']]);
                return ApiComponent::successResponse('Department updated successfully', $model, true);
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

        if (isset($request['id']) && isset($request['name'])) {

            $model = Department::find()->where('id='.$request['id'])->one();
            if($model) {
                Department::deleteAll('id='. $request['id']);
                return ApiComponent::successResponse('Department deleted successfully', $model, true);
            } else {
                return ApiComponent::errorResponse([], 1002);
            }

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    public function actionExpertDepartments() {
//        $model = new ExpertDepartment();

        $departments = \yii\helpers\ArrayHelper::map(Department::find()->all(), 'id', 'name');

        $users = \app\models\User::findUsersByRole('expert');
        $users = \yii\helpers\ArrayHelper::map($users, 'id', 'username');

//        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())){
//
//            $model->created_at = time();
//            $model->save();
//
//            Log::addLog(Log::AddExpertToDepartment, $model->expert_id . '-' . $model->department_id);
//
//            Yii::$app->session->setFlash('success', 'اطلاعات با موفقیت ذخیره شد');
//        }

        $query = (new Query())
            ->select(['expert_department.id', 'user.username', 'department.name', 'expert_department.created_at'])
            ->from('expert_department')
            ->leftJoin('user', 'user.id=expert_department.expert_id')
            ->leftJoin('department', 'department.id=expert_department.department_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $data = $dataProvider->getModels();
        $index = 0;
        foreach ($data as $department) {
            $department['created_at'] = Jdf::jdate('Y/m/d H:i', $department['created_at']);
            $data[$index++] = $department;
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
            'expertDeps' => $list,
            'departments' => $departments,
            'experts' => $users,
        ];

        return ApiComponent::successResponse('Expert Departments list', [
            'data' => $resultData,
        ], true);
    }

    public function actionAddExpertToDepartment() {
        $request = ApiComponent::parseInputData();

        if (isset($request['expert_id']) && isset($request['department_id'])) {
            $model = new ExpertDepartment();
            $model->expert_id = $request['expert_id'];
            $model->department_id = $request['department_id'];
            $model->created_at = time();
            $model->save();

            Log::addLog(Log::AddExpertToDepartment, $model->expert_id . '-' . $model->department_id);

            return ApiComponent::successResponse('Expert added to Department successfully', [
                $model,
            ], true);

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    //remove expert from department
    public function actionDeleteExpertFromDepartment() {
        $request = ApiComponent::parseInputData();

        if (isset($request['id'])) {
            $expertDepartment = ExpertDepartment::find()->where('id='.$request['id'])->one();

            if($expertDepartment) {
                $expertDepartment->delete();
                return ApiComponent::successResponse('Expert deleted from Department successfully', [
                    $expertDepartment,
                ], true);
            } else {
                return ApiComponent::errorResponse([], 1002);

            }

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }
}