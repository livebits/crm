<?php

namespace app\controllers;

use app\models\Source;
use Yii;
use yii\filters\AccessControl;
use app\models\Customer;
use app\models\CustomerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
{
    /**
     * {@inheritdoc}
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
//                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->searchClues(Yii::$app->request->queryParams);

        return $this->render('clues', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionContacts()
    {
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->searchContacts(Yii::$app->request->queryParams);

        return $this->render('contacts', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCustomers()
    {
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->searchCustomers(Yii::$app->request->queryParams);

        return $this->render('customers', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDeals()
    {
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->searchDeals(Yii::$app->request->queryParams);

        return $this->render('deals', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Customer model.
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
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Customer();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if(@$_FILES){
                $uploaded_files = $_FILES['Customer'];
                $file_name = $uploaded_files['name']['image'];
                if($file_name)
                {
                    $file_name = 'image'. time(). $file_name;
                    $file_tmp = $uploaded_files['tmp_name']['image'];
                    move_uploaded_file($file_tmp, 'Uploads/' . $file_name);

                    $model->image = $file_name;
                    $model->save();
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if(@$_FILES){
                $uploaded_files = $_FILES['Customer'];
                $file_name = $uploaded_files['name']['image'];
                if($file_name)
                {
                    $file_name = 'image'. time(). $file_name;
                    $file_tmp = $uploaded_files['tmp_name']['image'];
                    move_uploaded_file($file_tmp, 'Uploads/' . $file_name);

                    $model->image = $file_name;
                    $model->save();
                }
            }

            $updated_status = $model->status;

            if($updated_status == 0)
                return $this->redirect(['index']);
            else if($updated_status == 1)
                return $this->redirect(['customers']);
            else if($updated_status == 2)
                return $this->redirect(['deals']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Customer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = Customer::$CONTACT;
        $model->save();

        return $this->redirect(['contacts']);
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionConvertToClue($customer_id) {
        $model = $this->findModel($customer_id);
        $model->status = Customer::$CLUE;
        $model->save();

        return $this->redirect(['index']);
    }
}
