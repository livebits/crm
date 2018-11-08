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
use app\models\ProjectSearch;
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

class ProjectController extends \yii\rest\Controller
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

    /////////////////////////////     Projects     ///////////////////////////
    public function actionGetProjects() {
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);

        $data = $dataProvider->getModels();
        $index = 0;

        $page = $dataProvider->pagination->page + 1;
        $page_size = $dataProvider->pagination->pageSize;
        $pages = ceil($dataProvider->getTotalCount() / $page_size);

        return ApiComponent::successResponse('Projects list', [
            'data' => $data,
            'page' => $page,
            'page_size' => $page_size,
            'pages' => $pages
        ], true);
    }

    public function actionNew()
    {
        $request = ApiComponent::parseInputData();

        if (isset($request['title'])) {

            $model = new Project();
            $model->title = $request['title'];
            $model->programming_lang = isset($request['programming_lang']) ? $request['programming_lang'] : 0;
            $model->description = isset($request['description']) ? $request['description'] : '';

            if ($model->save()) {
                return ApiComponent::successResponse('Project saved successfully', $model, true);
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

        if (isset($request['id']) && isset($request['title']) 
        && isset($request['programming_lang']) && isset($request['description'])) {

            $model = Project::find()->where('id='.$request['id'])->one();
            if($model) {
                Project::updateAll(
                    [
                        'title' => $request['title'],
                        'programming_lang' => $request['programming_lang'],
                        'description' => $request['description'],
                    ],
                    ['id' => $request['id']]
                );
                return ApiComponent::successResponse('Project updated successfully', $model, true);
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

            $model = Project::find()->where('id='.$request['id'])->one();
            if($model) {
                Project::deleteAll('id='. $request['id']);
                return ApiComponent::successResponse('Project deleted successfully', $model, true);
            } else {
                return ApiComponent::errorResponse([], 1002);
            }

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }
    ////////////////////////////////////////////////////////////////////////

    public function actionGetProgrammingLangs() {
        
        $data = Project::languages();
        
        return ApiComponent::successResponse('Programming language list', $data, true);
    }

    ////////////////////////  experts in projects ///////////////////////////
    public function actionExpertsInProjects() {

        $projects = \yii\helpers\ArrayHelper::map(Project::find()->all(), 'id', 'title');

        $users = \app\models\User::findUsersByRole('expert');
        $users = \yii\helpers\ArrayHelper::map($users, 'id', 'username');

        $query = (new Query())
            ->select(['expert_project.id', 'user.username', 'project.title', 'expert_project.created_at'])
            ->from('expert_project')
            ->leftJoin('user', 'user.id=expert_project.expert_id')
            ->leftJoin('project', 'project.id=expert_project.project_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $data = $dataProvider->getModels();
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
            'expertsInProjects' => $list,
            'projects' => $projects,
            'experts' => $users,
        ];

        return ApiComponent::successResponse('Customer projects list', [
            'data' => $resultData,
        ], true);
    }

    public function actionAddExpertToProject() {
        $request = ApiComponent::parseInputData();

        if (isset($request['expert_id']) && isset($request['project_id'])) {
            $model = new ExpertProject();
            $model->expert_id = $request['expert_id'];
            $model->project_id = $request['project_id'];
            $model->created_at = time();
            $model->save();

            return ApiComponent::successResponse('Expert added to Project successfully', [
                $model,
            ], true);

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    //remove expert from project
    public function actionDeleteExpertFromProject() {
        $request = ApiComponent::parseInputData();

        if (isset($request['id'])) {
            $expertProject = ExpertProject::find()->where('id='.$request['id'])->one();

            if($expertProject) {
                $expertProject->delete();
                return ApiComponent::successResponse('expert deleted from project successfully', [
                    $expertProject,
                ], true);
            } else {
                return ApiComponent::errorResponse([], 1002);

            }

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    public function actionGetExpertProjects() {
        $projects = (new \yii\db\Query())
            ->from('expert_project')
            ->select(['project.id as id', 'project.title as title'])
            ->leftJoin('project', 'project.id=expert_project.project_id')
            ->where('expert_project.expert_id=' . Yii::$app->user->id)
            ->all();

        return ApiComponent::successResponse('Expert Projects list returned successfully', $projects, true);
    }
    ////////////////////////////////////////////////////////////////////////
}