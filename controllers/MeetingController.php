<?php

namespace app\controllers;

use app\components\Jdf;
use app\models\Customer;
use app\models\Deal;
use app\models\Media;
use app\models\MediaFile;
use app\models\Task;
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
use yii\filters\AccessControl;

/**
 * MeetingController implements the CRUD actions for Meeting model.
 */
class MeetingController extends Controller
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
     * Lists all Meeting models.
     * @return mixed
     */
    public function actionIndex($customer_id)
    {
        $searchModel = new MeetingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $customer_id);
        $customer = Customer::find()->where('id=' . $customer_id)->one();
        $tasks = Task::find()
            ->where('customer_id=' . $customer_id)
            ->orderBy('created_at DESC')
            ->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'customer' => $customer,
            'tasks' => $tasks,
        ]);
    }

    public function actionDealIndex($deal_id)
    {
        $searchModel = new MeetingSearch();
        $dataProvider = $searchModel->searchForDeals(Yii::$app->request->queryParams, $deal_id);
        $deal = Deal::find()->where('id=' . $deal_id)->one();
        $customer = Customer::find()->where('id=' . $deal->customer_id)->one();
        $tasks = Task::find()
            ->where('deal_id=' . $deal_id)
            ->orderBy('created_at DESC')
            ->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'customer' => $customer,
            'tasks' => $tasks,
        ]);
    }

    public function actionLateCustomers() {

        $customers_meetings = Meeting::find()
            ->leftJoin('customer', 'customer.id=meeting.customer_id')
            ->where('deal_id IS NULL')
            ->andWhere('next_date IS NOT NULL')
            ->andWhere('customer.status != ' . Customer::$OFF_CUSTOMER)
            ->orderBy('created_at DESC')
            ->all();

        $lateMeetingsIds = [];
        $lateMeetingsIds[] = -1;
        $customer_ids = [];
        foreach ($customers_meetings as $customers_meeting) {

            if (in_array($customers_meeting->customer_id, $customer_ids)) {
                continue;
            }

            $customer_ids[] = $customers_meeting->customer_id;
            if(date('Y-m-d', $customers_meeting->next_date) < date('Y-m-d', time())){

                $lateMeetingsIds[] = $customers_meeting->id;
            }
        }

        $lateMeetingsIds = implode(",",$lateMeetingsIds);

        $searchModel = new MeetingSearch();
        $dataProvider = $searchModel->searchCustomersLates(Yii::$app->request->queryParams, $lateMeetingsIds);

        return $this->render('late-customers', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionLateDeals() {

        $deals_meetings = Meeting::find()
            ->where('customer_id IS NULL')
            ->andWhere('next_date IS NOT NULL')
            ->orderBy('created_at DESC')
            ->all();

        $lateMeetingsIds = [];
        $lateMeetingsIds[] = -1;
        $deal_ids = [];
        foreach ($deals_meetings as $deals_meeting) {

            if (in_array($deals_meeting->deal_id, $deal_ids)) {
                continue;
            }

            $deal_ids[] = $deals_meeting->deal_id;
            if(date('Y-m-d', $deals_meeting->next_date) < date('Y-m-d', time())){

                $lateMeetingsIds[] = $deals_meeting->id;
            }
        }

        $lateMeetingsIds = implode(",",$lateMeetingsIds);

        $searchModel = new MeetingSearch();
        $dataProvider = $searchModel->searchDealsLates(Yii::$app->request->queryParams, $lateMeetingsIds);

        return $this->render('late-deals', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
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

    public function actionViewDeal($id, $deal_id)
    {
        return $this->render('view-deal', [
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

    public function actionCreateDealMeeting($deal_id)
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
            $meeting->deal_id = intval($deal_id);
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

            return $this->redirect(['view-deal', 'id' => $meeting->id, 'deal_id' => $deal_id]);
        }

        $deal = Deal::find()->where('id=' . $deal_id)->one();
        $user = (new \yii\db\Query())
            ->select('*')
            ->from('user')
            ->where('id=' . Yii::$app->user->id)
            ->one();

        return $this->render('create-deal-meeting', [
            'model' => $model,
            'deal' => $deal,
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


    public function actionUpdateDeal($id, $deal_id)
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

            return $this->redirect(['view-deal', 'id' => $meeting->id, 'deal_id' => $deal_id]);
        }

        $deal = Deal::find()->where('id=' . $deal_id)->one();
        $user = (new \yii\db\Query())
            ->select('*')
            ->from('user')
            ->where('id=' . Yii::$app->user->id)
            ->one();

        return $this->render('update-deal', [
            'model' => $meeting,
            'deal' => $deal,
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
