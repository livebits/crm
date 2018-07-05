<?php

namespace app\controllers;

use app\components\Jdf;
use app\models\Customer;
use app\models\Media;
use app\models\MediaFile;
use app\models\User;
use Yii;
use app\models\Meeting;
use app\models\MeetingSearch;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * MeetingController implements the CRUD actions for Meeting model.
 */
class MeetingController extends Controller
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
     * Lists all Meeting models.
     * @return mixed
     */
    public function actionIndex($customer_id)
    {
        $searchModel = new MeetingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $customer_id);
        $customer = Customer::find()->where('id=' . $customer_id)->one();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'customer' => $customer,
        ]);
    }

    /**
     * Displays a single Meeting model.
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
     * Creates a new Meeting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($customer_id)
    {
        $model = new Meeting();
        $imageMediaFile = new MediaFile();
        $soundMediaFile = new MediaFile();
        $otherMediaFile = new MediaFile();

        $request = Yii::$app->request;

        if ($request->isPost) {
            $imageFileIds = $request->post('imageFiles');
            $audioFileIds = $request->post('audioFiles');
            $otherFileIds = $request->post('otherFiles');

            $meetingData = $request->post('Meeting');

            //add new meeting
            $meeting = new Meeting();
            $meeting->user_id = Yii::$app->user->id;
            $meeting->customer_id = intval($customer_id);
            $meeting->content = $meetingData['content'];
            $meeting->rating = isset($meetingData['rating']) ? intval($meetingData['rating']) : 1;

            $created_at_date = explode('/', $meetingData['created_at']);
            $created_at_date_g = Jdf::jalali_to_gregorian($created_at_date[0], $created_at_date[1], $created_at_date[2], '-');
            $meeting->created_at = strtotime($created_at_date_g);

            $next_date = explode('/', $meetingData['next_date']);
            $next_date_g = Jdf::jalali_to_gregorian($next_date[0], $next_date[1], $next_date[2], '-');
            $meeting->next_date = strtotime($next_date_g);

            $meeting->save(false);

            if ($imageFileIds != "") {
                $mediaIds = explode(',', $imageFileIds);

                foreach ($mediaIds as $mediaId) {
                    Media::updateAll(['meeting_id' => $meeting->id], ['id' => $mediaId]);
                }
            }

            if ($audioFileIds != "") {
                $mediaIds = explode(',', $audioFileIds);

                foreach ($mediaIds as $mediaId) {
                    Media::updateAll(['meeting_id' => $meeting->id], ['id' => $mediaId]);
                }
            }

            if ($otherFileIds != "") {
                $mediaIds = explode(',', $otherFileIds);

                foreach ($mediaIds as $mediaId) {
                    Media::updateAll(['meeting_id' => $meeting->id], ['id' => $mediaId]);
                }
            }

            return $this->redirect(['view', 'id' => $meeting->id, 'customer_id' => $customer_id]);
        }

        $customer = Customer::find()->where('id=' . $customer_id)->one();
        $user = (new \yii\db\Query())
            ->select('*')
            ->from('user')
            ->where('id=' . Yii::$app->user->id)
            ->one();

        return $this->render('create', [
            'model' => $model,
            'customer' => $customer,
            'user' => $user,
            'imageMediaFile' => $imageMediaFile,
            'soundMediaFile' => $soundMediaFile,
            'otherMediaFile' => $otherMediaFile,
        ]);
    }

    public function UploadFiles($dir, $type){

        $uploaded_files = $_FILES['MediaFile'];
        foreach ($uploaded_files['name'] as $key => $file_name) {
            if ($file_name) {
                $uid = uniqid(time(), true);
                $file_name = $uid . '_' . $file_name;
                $file_tmp = $uploaded_files['tmp_name'][$key];
                move_uploaded_file($file_tmp, 'media/' . $dir . '/' . $file_name);

                $path = Yii::getAlias('@web') . '/media/' . $dir . '/' . $file_name;

                $media = new Media();
                $media->type = $type;
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
                            'deleteUrl' => 'media-delete?name=' . $file_name . '&media_id=' . $media_id . '&type=image',
                            'deleteType' => 'POST',
                        ],
                    ],
                ]);
            }
        }
    }

    public function actionUploadImages()
    {

        return $this->UploadFiles('images', Media::$IMAGE);
    }

    public function actionUploadSounds()
    {
        return $this->UploadFiles('audio', Media::$AUDIO);
    }

    public function actionUploadOther()
    {
        return $this->UploadFiles('other', Media::$OTHER);
    }

    public function actionMediaDelete($name, $media_id, $type = "image", $myFiles = "")
    {

        if ($type == "image") {
            $directory = Yii::getAlias('@web') . '/media/images';
        } else if ($type == "audio") {
            $directory = Yii::getAlias('@web') . '/media/audio';
        } else {
            $directory = Yii::getAlias('@web') . '/media/other';
        }
        if (is_file($_SERVER["DOCUMENT_ROOT"] . $directory . '/' . $name)) {
            unlink($_SERVER["DOCUMENT_ROOT"] . $directory . '/' . $name);

            //delete from db
            Media::deleteAll(['id' => $media_id]);
        }

        $files = FileHelper::findFiles($directory);
        $output = [];
        foreach ($files as $file) {
            $fileName = basename($file);
            $path = $directory . '/' . $fileName;
            $output['files'][] = [
                'name' => $fileName,
                'size' => filesize($file),
                'url' => $path,
                'thumbnailUrl' => $path,
                'deleteUrl' => 'image-delete?name=' . $fileName,
                'deleteType' => 'POST',
            ];
        }
        return Json::encode($output);
    }

    /**
     * Updates an existing Meeting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $customer_id)
    {
        $meeting = $this->findModel($id);
        $imageMediaFile = new MediaFile();
        $soundMediaFile = new MediaFile();
        $otherMediaFile = new MediaFile();

        $request = Yii::$app->request;
        if ($request->isPost) {
            $imageFileIds = $request->post('imageFiles');
            $audioFileIds = $request->post('audioFiles');
            $otherFileIds = $request->post('otherFiles');

            $meetingData = $request->post('Meeting');

            //add new meeting
//            $meeting->user_id = Yii::$app->user->id;
//            $meeting->customer_id = intval($customer_id);
            $meeting->content = $meetingData['content'];
            $meeting->rating = isset($meetingData['rating']) ? intval($meetingData['rating']) : 1;

            $created_at_date = explode('/', $meetingData['created_at']);
            $created_at_date_g = Jdf::jalali_to_gregorian($created_at_date[0], $created_at_date[1], $created_at_date[2], '-');
            $meeting->created_at = strtotime($created_at_date_g);

            $next_date = explode('/', $meetingData['next_date']);
            $next_date_g = Jdf::jalali_to_gregorian($next_date[0], $next_date[1], $next_date[2], '-');
            $meeting->next_date = strtotime($next_date_g);

            $meeting->save(false);

            if ($imageFileIds != "") {
                $mediaIds = explode(',', $imageFileIds);

                foreach ($mediaIds as $mediaId) {
                    Media::updateAll(['meeting_id' => $meeting->id], ['id' => $mediaId]);
                }
            }

            if ($audioFileIds != "") {
                $mediaIds = explode(',', $audioFileIds);

                foreach ($mediaIds as $mediaId) {
                    Media::updateAll(['meeting_id' => $meeting->id], ['id' => $mediaId]);
                }
            }

            if ($otherFileIds != "") {
                $mediaIds = explode(',', $otherFileIds);

                foreach ($mediaIds as $mediaId) {
                    Media::updateAll(['meeting_id' => $meeting->id], ['id' => $mediaId]);
                }
            }

            return $this->redirect(['view', 'id' => $meeting->id, 'customer_id' => $customer_id]);
        }

        $customer = Customer::find()->where('id=' . $customer_id)->one();
        $user = (new \yii\db\Query())
            ->select('*')
            ->from('user')
            ->where('id=' . Yii::$app->user->id)
            ->one();

        return $this->render('update', [
            'model' => $meeting,
            'customer' => $customer,
            'user' => $user,
            'imageMediaFile' => $imageMediaFile,
            'soundMediaFile' => $soundMediaFile,
            'otherMediaFile' => $otherMediaFile,
        ]);
    }

    /**
     * Deletes an existing Meeting model.
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
     * Finds the Meeting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Meeting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Meeting::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
