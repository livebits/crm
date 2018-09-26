<?php

namespace app\controllers;

use app\models\Deal;
use app\models\Department;
use app\models\ExpertDepartment;
use app\models\ExpertTicket;
use app\models\Log;
use app\models\Media;
use app\models\MediaFile;
use app\models\User;
use Yii;
use app\models\Ticket;
use app\models\TicketSearch;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TicketController implements the CRUD actions for Ticket model.
 */
class TicketController extends Controller
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
     * Lists all Ticket models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TicketSearch();

        $params = Yii::$app->request->queryParams;
        $params['TicketSearch']['user_id'] = Yii::$app->user->id . '';
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * @return string
     */
    public function actionExpertTickets()
    {
        $searchModel = new TicketSearch();

        $params = Yii::$app->request->queryParams;

        //find expert department
        $expertDeps = ExpertDepartment::find()
            ->select('department_id')
            ->where('expert_id=' . Yii::$app->user->id)
            ->asArray()
            ->all();
        $deps = [];
        $deps[] = -1;
        foreach ($expertDeps as $expertDep) {
            $deps[] = intval($expertDep['department_id']);
        }

        //expert tickets
        $expertTickets = ExpertTicket::find()
            ->select('ticket_id')
            ->where('expert_id=' . Yii::$app->user->id)
            ->asArray()
            ->all();
        $tickets_id = [];
        $tickets_id[] = -1;
        foreach ($expertTickets as $expertTicket) {
            $tickets_id[] = intval($expertTicket['ticket_id']);
        }
        $dataProvider = $searchModel->search($params, $deps, $tickets_id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionAllTickets()
    {
        $searchModel = new TicketSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Ticket model.
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
     * Creates a new Ticket model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ticket();

        $user_deals = \yii\helpers\ArrayHelper::map(
            Deal::find()
                ->leftJoin('user_deal', 'user_deal.deal_id=deal.id')
                ->where('user_deal.user_id=' . Yii::$app->user->id)
                ->all(),
            'id', 'subject');
        $departments = \yii\helpers\ArrayHelper::map(Department::find()->all(), 'id', 'name');
        $mediaFile = new MediaFile();

        $request = Yii::$app->request;

        if ($model->load(Yii::$app->request->post())) {
            $otherFileIds = $request->post('mediaFiles');

            $model->user_id = Yii::$app->user->id;
            $model->created_at = time();
            $model->status = Ticket::NOT_CHECKED;

            if ($model->save()) {

                if ($otherFileIds != "") {
                    $mediaIds = explode(',', $otherFileIds);

                    foreach ($mediaIds as $mediaId) {
                        Media::updateAll(['meeting_id' => $model->id], ['id' => $mediaId]);
                    }
                }

                Log::addLog(Log::AddNewTicket, $model->id);

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'user_deals' => $user_deals,
            'departments' => $departments,
            'mediaFile' => $mediaFile,
        ]);
    }

    /**
     * Updates an existing Ticket model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $user_deals = \yii\helpers\ArrayHelper::map(Deal::find()->where('id > 0')->all(), 'id', 'subject');
        $departments = \yii\helpers\ArrayHelper::map(Department::find()->all(), 'id', 'name');
        $mediaFile = new MediaFile();

        $request = Yii::$app->request;
        if ($model->load(Yii::$app->request->post())) {

            $otherFileIds = $request->post('mediaFiles');

            $model->updated_at = time();
            $model->status = Ticket::NOT_CHECKED;

            if ($model->save()) {

                if ($otherFileIds != "") {
                    $mediaIds = explode(',', $otherFileIds);

                    foreach ($mediaIds as $mediaId) {
                        Media::updateAll(['meeting_id' => $model->id], ['id' => $mediaId]);
                    }
                }

                Log::addLog(Log::AddNewTicket, $model->id . '-' . $model->status);

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'user_deals' => $user_deals,
            'departments' => $departments,
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
                move_uploaded_file($file_tmp, 'media/tickets/attachments/' . $file_name);

                $path = Yii::getAlias('@web') . '/media/tickets/attachments/' . $file_name;

                $media = new Media();
                $media->type = 'TICKET_ATTACHMENT';
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

        $directory = Yii::getAlias('@web') . '/media/tickets/attachments';

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
     * Deletes an existing Ticket model.
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
     * Finds the Ticket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ticket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ticket::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDetails()
    {
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('expandRowKey');
            $model = $this->findModel(intval($id));

            return $this->renderAjax('details', compact('model'));
        }

        return '';
    }

    public function actionReply($id)
    {

        $model = $this->findModel(intval($id));
        $ticket = new Ticket();
        $ticket->department = $model->department;
        $ticket->deal_id = $model->deal_id;
        $ticket->reply_to = $model->id;

        $ticket->status = User::is_in_role(Yii::$app->user->id, 'customer') ? Ticket::CUSTOMER_REPLIED : Ticket::EXPERT_REPLIED;

        if ($ticket->load(Yii::$app->request->post())) {

            $ticket->user_id = Yii::$app->user->id;
            $ticket->created_at = time();

            if ($ticket->save()) {

                if (!Yii::$app->user->isSuperadmin && User::is_in_role(Yii::$app->user->id, 'customer')) {
                    $model->status = Ticket::NEED_EXPERT_REPLY;
                } else if (User::is_in_role(Yii::$app->user->id, 'expert')) {
                    $model->status = Ticket::NEED_CUSTOMER_REPLY;
                }

                $model->save();

                Log::addLog(Log::ReplyTicket, $model->id . '-' . $model->status);

                Yii::$app->session->setFlash('success', 'پاسخ شما با موفقیت ذخیره شد');
            }
        }

        $tickets = Ticket::find()
            ->where('id=' . $id)
            ->orWhere('reply_to=' . $id)
            ->orderBy('created_at ASC');
        $dataProvider = new ActiveDataProvider([
            'query' => $tickets,
        ]);

        return $this->render('reply', [
            'ticket' => $ticket,
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCheck($id)
    {
        $model = $this->findModel(intval($id));
        $model->status = Ticket::PENDING;
        $model->save();

        Log::addLog(Log::CheckTicket, $model->id . '-' . $model->status);

        return $this->redirect('expert-tickets');
    }

    public function actionClose($id)
    {
        $model = $this->findModel(intval($id));
        $model->status = Ticket::CLOSED;
        $model->save();

        Log::addLog(Log::CloseTicket, $model->id . '-' . $model->status);

        if (User::is_in_role(Yii::$app->user->id, 'customer')) {
            return $this->redirect('index');
        } else {
            return $this->redirect('expert-tickets');
        }
    }

    /*
     * Add user(expert) to one or more tickets
     */
    public function actionAddExpertTicket() {
        $model = new ExpertTicket();

        $tickets = \yii\helpers\ArrayHelper::map(Ticket::find()->all(), 'id', 'title');

        $users = User::findUsersByRole('expert');
        $users = \yii\helpers\ArrayHelper::map($users, 'id', 'username');

        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())){

            $model->created_at = time();
            $model->save();

            Log::addLog(Log::AddTicketForExpert, $model->ticket_id . '-' . $model->expert_id);

            Yii::$app->session->setFlash('success', 'اطلاعات با موفقیت ذخیره شد');
        }

        $query = (new Query())
            ->select(['expert_ticket.id', 'user.username', 'ticket.title', 'expert_ticket.created_at'])
            ->from('expert_ticket')
            ->leftJoin('user', 'user.id=expert_ticket.expert_id')
            ->leftJoin('ticket', 'ticket.id=expert_ticket.ticket_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('add-expert-tickets', [
            'dataProvider' => $dataProvider,
            'model' => $model,
            'users' => $users,
            'tickets' => $tickets
        ]);
    }

    //remove expert from ticket
    public function actionDeleteExpertTicket($id) {

        $expertTicket = ExpertTicket::find()->where('id='.$id)->one();

        if($expertTicket) {
            $expertTicket->delete();
            return $this->redirect('add-expert-ticket');
        }

        throw new ForbiddenHttpException('شما اجازه دسترسی به این بخش را ندارید');
    }
}
