<?php

namespace app\controllers;

use app\models\ExpertDepartment;
use app\models\Log;
use app\models\User;
use Yii;
use app\models\Department;
use app\models\DepartmentSearch;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * DepartmentController implements the CRUD actions for Department model.
 */
class DepartmentController extends Controller
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
     * Lists all Department models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DepartmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Department model.
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
     * Creates a new Department model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Department();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Department model.
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
     * Deletes an existing Department model.
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
     * Finds the Department model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Department the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Department::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /*
     * Add user(expert) to one or more departments
     */
    public function actionAddExpertDepartment() {
        $model = new ExpertDepartment();

        $departments = \yii\helpers\ArrayHelper::map(Department::find()->all(), 'id', 'name');

        $users = User::findUsersByRole('expert');
        $users = \yii\helpers\ArrayHelper::map($users, 'id', 'username');

        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())){

            $model->created_at = time();
            $model->save();

            Log::addLog(Log::AddExpertToDepartment, $model->expert_id . '-' . $model->department_id);

            Yii::$app->session->setFlash('success', 'اطلاعات با موفقیت ذخیره شد');
        }

        $query = (new Query())
            ->select(['expert_department.id', 'user.username', 'department.name', 'expert_department.created_at'])
            ->from('expert_department')
            ->leftJoin('user', 'user.id=expert_department.expert_id')
            ->leftJoin('department', 'department.id=expert_department.department_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('add-expert-department', [
            'dataProvider' => $dataProvider,
            'model' => $model,
            'users' => $users,
            'departments' => $departments
        ]);
    }

    //remove expert from department
    public function actionDeleteExpertDepartment($id) {

        $expertDepartment = ExpertDepartment::find()->where('id='.$id)->one();

        if($expertDepartment) {
            $expertDepartment->delete();
            return $this->redirect('add-expert-department');
        }

        throw new ForbiddenHttpException('شما اجازه دسترسی به این بخش را ندارید');
    }
}
