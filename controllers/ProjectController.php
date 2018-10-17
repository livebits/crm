<?php

namespace app\controllers;

use app\models\ExpertProject;
use app\models\Log;
use app\models\User;
use Yii;
use app\models\Project;
use app\models\ProjectSearch;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Project model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Project();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /*
     * Add user(expert) to one or more projects
     */
    public function actionAddExpertProject()
    {
        $model = new ExpertProject();

        $projects = \yii\helpers\ArrayHelper::map(Project::find()->all(), 'id', 'title');

        $experts = User::findUsersByRole('expert');
        $admins = User::findUsersByRole('Admin');
        $users = [];
        foreach ($experts as $expert) {
            $users[] = $expert;
        }
        foreach ($admins as $admin) {
            $users[] = $admin;
        }
        $users = \yii\helpers\ArrayHelper::map($users, 'id', 'username');

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            $model->created_at = time();
            $model->save();

            Log::addLog(Log::AddProjectForExpert, $model->project_id . '-' . $model->expert_id);

            Yii::$app->session->setFlash('success', 'اطلاعات با موفقیت ذخیره شد');
        }

        $query = (new Query())
            ->select(['expert_project.id', 'user.username', 'project.title', 'expert_project.created_at'])
            ->from('expert_project')
            ->leftJoin('user', 'user.id=expert_project.expert_id')
            ->leftJoin('project', 'project.id=expert_project.project_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('add-expert-projects', [
            'dataProvider' => $dataProvider,
            'model' => $model,
            'users' => $users,
            'projects' => $projects
        ]);
    }

    //remove expert from ticket
    public function actionDeleteExpertProject($id)
    {

        $expertProject = ExpertProject::find()->where('id=' . $id)->one();

        if ($expertProject) {
            $expertProject->delete();
            return $this->redirect('add-expert-project');
        }

        throw new ForbiddenHttpException('شما اجازه دسترسی به این بخش را ندارید');
    }
}
