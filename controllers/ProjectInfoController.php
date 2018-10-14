<?php

namespace app\controllers;

use Yii;
use app\models\ProjectInfo;
use app\models\ProjectInfoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProjectInfoController implements the CRUD actions for ProjectInfo model.
 */
class ProjectInfoController extends Controller
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
     * Lists all ProjectInfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProjectInfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProjectInfo model.
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
     * Creates a new ProjectInfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProjectInfo();

        if ($model->load(Yii::$app->request->post())) {

            $transaction = Yii::$app->getDb()->beginTransaction();
            $dbSuccess = true;

            if (!$model->save())
                $dbSuccess = false;
            if ($dbSuccess) {
                $prefix = "" . time();
                $sign_file_name = '';
                $keystore_file_name = '';

                // Upload Files
                if ($_FILES) {
                    if (isset($_FILES['ProjectInfo']['name']['sign_file'])) {
//                        $uploaded_files = $_FILES['ProjectInfo']['name']['sign_file'];
//                        foreach ($uploaded_files['name'] as $key => $file_name) {
//                            if ($file_name) {
                        $uid = uniqid(time(), true);
                        $file_name = $uid . '_' . $model->id . '_' . $_FILES['ProjectInfo']['name']['sign_file'];
                        $file_tmp = $_FILES['ProjectInfo']['tmp_name']['sign_file'];
                        $sign_file_name = $file_name;
                        move_uploaded_file($file_tmp, 'media/project/attachments/' . $file_name);
//                            }
//                        }
                    }

                    if (isset($_FILES['ProjectInfo']['name']['keystore'])) {
//                        $uploaded_files = $_FILES['ProjectInfo']['keystore'];
//                        foreach ($uploaded_files['name'] as $key => $file_name) {
//                            if ($file_name) {
                        $uid = uniqid(time(), true);
                        $file_name = $uid . '_' . $model->id . '_' . $_FILES['ProjectInfo']['name']['keystore'];
                        $file_tmp = $_FILES['ProjectInfo']['tmp_name']['keystore'];
                        $keystore_file_name = $file_name;
                        move_uploaded_file($file_tmp, 'media/project/attachments/' . $file_name);
//                            }
//                        }
                    }
                }
                $transaction->commit();

                ProjectInfo::updateAll(['sign_file' => $sign_file_name, 'keystore' => $keystore_file_name], ['id' => $model->id]);

                Yii::$app->session->setFlash('success', 'اطلاعات با موفقیت ذخیره شد.');
                return $this->redirect('index');
            } else {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'خطایی در  ثبت اطلاعات پیش آمد.');
            }

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProjectInfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $sign_file_name = '';
            $keystore_file_name = '';

            // Upload Files
            if ($_FILES) {
                if (isset($_FILES['ProjectInfo']['name']['sign_file']) && $_FILES['ProjectInfo']['name']['sign_file'] != "") {
                    $uid = uniqid(time(), true);
                    $file_name = $uid . '_' . $model->id . '_' . $_FILES['ProjectInfo']['name']['sign_file'];
                    $file_tmp = $_FILES['ProjectInfo']['tmp_name']['sign_file'];
                    $sign_file_name = $file_name;
                    move_uploaded_file($file_tmp, 'media/project/attachments/' . $file_name);
                }

                if (isset($_FILES['ProjectInfo']['name']['keystore']) && $_FILES['ProjectInfo']['name']['keystore'] != "") {
                    $uid = uniqid(time(), true);
                    $file_name = $uid . '_' . $model->id . '_' . $_FILES['ProjectInfo']['name']['keystore'];
                    $file_tmp = $_FILES['ProjectInfo']['tmp_name']['keystore'];
                    $keystore_file_name = $file_name;
                    move_uploaded_file($file_tmp, 'media/project/attachments/' . $file_name);
                }
            }

            $update_arr = [
                'project_id' => $model->project_id,
                'publish_version' => $model->publish_version,
                'package_name' => $model->package_name,
                'api_key' => $model->api_key,
                'key_alias_password' => $model->key_alias_password,
            ];

            if($sign_file_name != '') {
                $update_arr['sign_file'] = $sign_file_name;
            }

            if($keystore_file_name != '') {
                $update_arr['keystore'] = $keystore_file_name;
            }

            ProjectInfo::updateAll(
                $update_arr,
                ['id' => $model->id]);

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ProjectInfo model.
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
     * Finds the ProjectInfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProjectInfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProjectInfo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
