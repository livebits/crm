<?php

namespace app\controllers;

use Yii;
use app\models\Task;
use app\models\TaskSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Task models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Task model.
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
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Task();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionAddTask()
    {
        $model = new Task();

        $request = Yii::$app->request;
        $name = $request->post('task_name');

        if($request->post('deal_id') != null){
            $deal_id = $request->post('deal_id');
            $model->deal_id = $deal_id;

        } else {
            $customer_id = $request->post('customer_id');
            $model->customer_id = $customer_id;
        }

        $model->name = $name;
        $model->created_at = time();
        $model->save(false);

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Updates an existing Task model.
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

    public function actionSaveChanges() {

        $request = Yii::$app->request;
        $tasks = $request->post('task');
        $done_tasks_ids = [];

        if(!isset($tasks)){
            $tasks = [];
        }

        foreach ($tasks as $task_id => $task_value){
            $done_tasks_ids[] = $task_id;
        }

        if($request->post('deal_id') != null) {

            $deal_id = $request->post('deal_id');

            $deal_tasks = Task::find()->where('deal_id=' . $deal_id)->all();

            foreach ($deal_tasks as $deal_task) {
                if(in_array($deal_task->id, $done_tasks_ids)){
                    Task::updateAll(['is_done' => '1'], 'id='.$deal_task->id);
                } else {
                    Task::updateAll(['is_done' => '0'], 'id='.$deal_task->id);
                }
            }

        } else if($request->post('customer_id') != null) {
            $customer_id = $request->post('customer_id');

            $customer_tasks = Task::find()->where('customer_id=' . $customer_id)->all();

            foreach ($customer_tasks as $customer_task) {
                if(in_array($customer_task->id, $done_tasks_ids)){
                    Task::updateAll(['is_done' => '1'], 'id='.$customer_task->id);
                } else {
                    Task::updateAll(['is_done' => '0'], 'id='.$customer_task->id);
                }
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing Task model.
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

    public function actionDeleteTask($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
