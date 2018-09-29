<?php

namespace app\controllers;

use app\models\Log;
use app\models\Media;
use app\models\MediaFile;
use Yii;
use app\models\Receipt;
use app\models\ReceiptSearch;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ReceiptController implements the CRUD actions for Receipt model.
 */
class ReceiptController extends Controller
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
     * Lists all Receipt models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReceiptSearch();
        $params = Yii::$app->request->queryParams;
        $params['ReceiptSearch']['user_id'] = Yii::$app->user->id . '';
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Receipt model.
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
     * Creates a new Receipt model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Receipt();
        $mediaFile = new MediaFile();

        if ($model->load(Yii::$app->request->post())) {
            $otherFileIds = Yii::$app->request->post('mediaFiles');

            $model->user_id = Yii::$app->user->id;
            $model->created_at = time();

            if($model->save()) {

                if ($otherFileIds != "") {
                    $mediaIds = explode(',', $otherFileIds);

                    foreach ($mediaIds as $mediaId) {
                        Media::updateAll(['meeting_id' => $model->id], ['id' => $mediaId]);
                    }
                }

                Log::addLog(Log::AddNewReceipt, $model->id);

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'mediaFile' => $mediaFile,
        ]);
    }

    /**
     * Updates an existing Receipt model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $mediaFile = new MediaFile();

        if ($model->load(Yii::$app->request->post())) {

            $otherFileIds = Yii::$app->request->post('mediaFiles');

            $model->updated_at = time();

            if($model->save()) {

                if ($otherFileIds != "") {
                    $mediaIds = explode(',', $otherFileIds);

                    foreach ($mediaIds as $mediaId) {
                        Media::updateAll(['meeting_id' => $model->id], ['id' => $mediaId]);
                    }
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'mediaFile' => $mediaFile,
        ]);
    }

    public function actionUploadAttachment()
    {

        $uploaded_files = $_FILES['MediaFile'];
        foreach ($uploaded_files['name'] as $key => $file_name) {
            if ($file_name) {
                $uid = uniqid(time(), true);
                $file_name = $uid . '_' . $file_name;
                $file_tmp = $uploaded_files['tmp_name'][$key];
                move_uploaded_file($file_tmp, 'media/receipts/' . $file_name);

                $path = Yii::getAlias('@web') . '/media/receipts/' . $file_name;

                $media = new Media();
                $media->type = Media::$RECEIPT;
                $media->filename = $file_name;
                $media->created_at = time();
                $media->save();

                $media_id = $media->id;

                return Json::encode([
                    'files' => [
                        [
                            'name' => $file_name,
                            'media_id' => $media_id,
                            'size' => $uploaded_files['size'][$key],
                            'url' => $path,
                            'thumbnailUrl' => $path,
                            'deleteUrl' => 'media-delete?name=' . $file_name . '&media_id=' . $media_id,
                            'deleteType' => 'POST',
                        ],
                    ],
                ]);
            }
        }
    }

    public function actionMediaDelete($name, $media_id, $myFiles = "")
    {

        $directory = Yii::getAlias('@web') . '/media/receipts';

        if (is_file($_SERVER["DOCUMENT_ROOT"] . $directory . '/' . $name)) {
            unlink($_SERVER["DOCUMENT_ROOT"] . $directory . '/' . $name);

            //delete from db
            Media::deleteAll(['id' => $media_id]);
        }

        $files = FileHelper::findFiles($_SERVER["DOCUMENT_ROOT"] . $directory . '/');
        $output = [];
        foreach ($files as $file) {
            $fileName = basename($file);
            $path = $directory . '/' . $fileName;
            $output['files'][] = [
                'name' => $fileName,
                'size' => filesize($file),
                'url' => $path,
                'thumbnailUrl' => $path,
                'deleteUrl' => 'file-delete?name=' . $fileName,
                'deleteType' => 'POST',
            ];
        }
        return Json::encode($output);
    }

    /**
     * Deletes an existing Receipt model.
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
     * Finds the Receipt model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Receipt the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Receipt::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
