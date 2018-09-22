<?php

namespace app\controllers;

use app\components\Jdf;
use app\models\Log;
use app\models\User;
use app\models\UserDeal;
use Yii;
use app\models\Deal;
use app\models\DealSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DealController implements the CRUD actions for Deal model.
 */
class DealController extends Controller
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
     * Lists all Deal models.
     * @return mixed
     */
    public function actionIndex($customer_id)
    {
        $searchModel = new DealSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $customer_id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAll()
    {
        $searchModel = new DealSearch();
        $dataProvider = $searchModel->searchAll(Yii::$app->request->queryParams);

        return $this->render('all', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //Show user(customer) deals
    public function actionUserDeals()
    {
        $user_deals = UserDeal::find()->select('deal_id')->where('user_id=' . Yii::$app->user->id)->all();
        $user_deals_id = [];
        $user_deals_id[] = -1;
        foreach ($user_deals as $user_deal) {
            $user_deals_id[] = $user_deal->deal_id;
        }

        $searchModel = new DealSearch();
        $dataProvider = $searchModel->searchUserDeals(Yii::$app->request->queryParams, $user_deals_id);

        return $this->render('user_deals', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Deal model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $customer_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Deal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($customer_id)
    {
        $model = new Deal();

        if(Yii::$app->request->isPost){
            $deal = new Deal();
            $deal->customer_id = $customer_id;
            $deal->subject = Yii::$app->request->post('Deal')['subject'];
            $deal->price = Yii::$app->request->post('Deal')['price'];
            $deal->level = Yii::$app->request->post('Deal')['level'];

            $create_date = explode('/', Yii::$app->request->post('Deal')['created_at']);
            $create_date_g = Jdf::jalali_to_gregorian($create_date[0], $create_date[1], $create_date[2], '-');
            $deal->created_at = strtotime($create_date_g);

            $deal->save();

            return $this->redirect(['index',
                'customer_id' => $customer_id,
            ]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Deal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $customer_id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index',
                'customer_id' => $customer_id,
            ]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Deal model.
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
     * Finds the Deal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Deal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Deal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /*
     * Add user(customer) to one or more deals
     */
    public function actionAddUserDeal() {
        $model = new UserDeal();

        $deals = \yii\helpers\ArrayHelper::map(Deal::find()->all(), 'id', 'subject');

        $users = User::findUsersByRole('customer');
        $users = \yii\helpers\ArrayHelper::map($users, 'id', 'username');

        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())){

            $model->created_at = time();
            $model->save();
        }

        Log::addLog(Log::AddUserToDeal, $model->user_id . '-' . $model->deal_id);

        return $this->render('add-user-deal', [
            'model' => $model,
            'users' => $users,
            'deals' => $deals
        ]);
    }
}
