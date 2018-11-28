<?php

namespace app\modules\api\controllers;

use app\components\Jdf;
use app\models\Department;
use app\models\ExpertDepartment;
use app\models\ExpertTicket;
use app\models\TicketSearch;
use Yii;
use app\components\ApiComponent;
use app\models\Customer;
use app\models\Deal;
use app\models\Log;
use app\models\Media;
use app\models\Meeting;
use app\models\Ticket;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class TicketController extends \yii\rest\Controller
{
    public function beforeAction($action)
    {
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
                QueryParamAuth::className(),
            ],
        ];

        return $behaviors;
    }

    public function actionCustomerTickets()
    {

//        $request = ApiComponent::parseInputData();

//        if (isset($request['ticket_id'])) {

        $searchModel = new TicketSearch();

        $params = Yii::$app->request->queryParams;
        $params['TicketSearch']['user_id'] = Yii::$app->user->id . '';
        $dataProvider = $searchModel->search($params, null, null, true);

        $data = $dataProvider->getModels();
        $index = 0;
        foreach ($data as $ticket) {
            $ticket['status'] = Ticket::ticketStatus($ticket['status']);
            $ticket['created_at'] = Jdf::jdate('Y/m/d H:i', $ticket['created_at']);
            $data[$index++] = $ticket;
        }

        $page = $dataProvider->pagination->page + 1;
        $page_size = $dataProvider->pagination->pageSize;
        $pages = ceil($dataProvider->getTotalCount() / $page_size);

        return ApiComponent::successResponse('Tickets list', [
            'data' => $data,
            'page' => $page,
            'page_size' => $page_size,
            'pages' => $pages
        ], true);

//        } else {
//            return ApiComponent::errorResponse([], 1000);
//
//        }
    }

    public function actionExpertTickets()
    {

        //        $request = ApiComponent::parseInputData();

//        if (isset($request['ticket_id'])) {

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
        $dataProvider = $searchModel->search($params, $deps, $tickets_id, true);

        $data = $dataProvider->getModels();
        $index = 0;
        foreach ($data as $ticket) {
            $ticket['status'] = Ticket::ticketStatus($ticket['status']);
            $ticket['created_at'] = Jdf::jdate('Y/m/d H:i', $ticket['created_at']);
            $data[$index++] = $ticket;
        }

        $page = $dataProvider->pagination->page + 1;
        $page_size = $dataProvider->pagination->pageSize;
        $pages = ceil($dataProvider->getTotalCount() / $page_size);

        return ApiComponent::successResponse('Tickets list', [
            'data' => $data,
            'page' => $page,
            'page_size' => $page_size,
            'pages' => $pages
        ], true);

//        } else {
//            return ApiComponent::errorResponse([], 1000);
//
//        }
    }

    public function actionAdminTickets()
    {

        //        $request = ApiComponent::parseInputData();

//        if (isset($request['ticket_id'])) {

        $searchModel = new TicketSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, null, null, true);

        $data = $dataProvider->getModels();
        $index = 0;
        foreach ($data as $ticket) {
            $ticket['status'] = Ticket::ticketStatus($ticket['status']);
            $ticket['created_at'] = Jdf::jdate('Y/m/d H:i', $ticket['created_at']);
            $data[$index++] = $ticket;
        }

        $page = $dataProvider->pagination->page + 1;
        $page_size = $dataProvider->pagination->pageSize;
        $pages = ceil($dataProvider->getTotalCount() / $page_size);

        return ApiComponent::successResponse('Tickets list', [
            'data' => $data,
            'page' => $page,
            'page_size' => $page_size,
            'pages' => $pages
        ], true);

//        } else {
//            return ApiComponent::errorResponse([], 1000);
//
//        }
    }

    public function actionGetCustomerDepAndDeals()
    {
        $user_deals = \yii\helpers\ArrayHelper::map(
            Deal::find()
                ->leftJoin('user_deal', 'user_deal.deal_id=deal.id')
                ->where('user_deal.user_id=' . Yii::$app->user->id)
                ->all(),
            'id', 'subject');
        $departments = \yii\helpers\ArrayHelper::map(Department::find()->all(), 'id', 'name');

        $data = [
            'deals' => $user_deals,
            'departments' => $departments
        ];

        return ApiComponent::successResponse('Customer departments and deals', $data, true);
    }

    public function actionNew()
    {
        $request = Yii::$app->request->post();
//        $request = ApiComponent::parseInputData();

        if (isset($request['deal_id']) && isset($request['department'])
            && isset($request['title']) && isset($request['body'])) {

            $ticket = new Ticket();
            $ticket->deal_id = $request['deal_id'];
            $ticket->user_id = \Yii::$app->user->id;
            $ticket->department = $request['department'];
            $ticket->title = $request['title'];
            $ticket->body = $request['body'];
            $ticket->created_at = time();
            $ticket->status = Ticket::NOT_CHECKED;

            $transaction = Yii::$app->getDb()->beginTransaction();
            $dbSuccess = true;

            if (!$ticket->save()) {
                $dbSuccess = false;
            }

            if ($dbSuccess) {

                if (isset($_FILES['MediaFile'])) {
                    $uploaded_files = $_FILES['MediaFile'];
                    foreach ($uploaded_files['name'] as $key => $file_name) {
                        if ($file_name) {
                            $uid = uniqid(time(), true);
                            $file_name = $uid . '_' . $file_name;
                            $file_tmp = $uploaded_files['tmp_name'][$key];
                            move_uploaded_file($file_tmp, 'media/tickets/attachments/' . $file_name);

                            $path = \Yii::getAlias('@web') . '/media/tickets/attachments/' . $file_name;

                            $media = new Media();
                            $media->type = 'TICKET_ATTACHMENT';
                            $media->meeting_id = $ticket->id;
                            $media->filename = $file_name;
                            $media->created_at = time();
                            $media->save();
                        }
                    }
                }

                $transaction->commit();
                return ApiComponent::successResponse('Ticket saved successfully', $ticket, true);

            } else {
                $transaction->rollBack();
                return ApiComponent::errorResponse([], 500);
            }

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    public function actionUploadAttachment()
    {
        if (isset($_FILES['MediaFile'])) {
            $uploaded_files = $_FILES['MediaFile'];
            $uploaded_media_ids = [];
            foreach ($uploaded_files['name'] as $key => $file_name) {
                if ($file_name) {
                    $uid = uniqid(time(), true);
                    $file_name = $uid . '_' . $file_name;
                    $file_tmp = $uploaded_files['tmp_name'][$key];
                    move_uploaded_file($file_tmp, 'media/tickets/attachments/' . $file_name);

                    $path = \Yii::getAlias('@web') . '/media/tickets/attachments/' . $file_name;

                    $media = new Media();
                    $media->type = 'TICKET_ATTACHMENT';
                    $media->filename = $file_name;
                    $media->created_at = time();
                    $media->save();

                    $uploaded_media_ids[] = $media->id;

                }
            }
            return ApiComponent::successResponse('attachments uploaded successfully', $uploaded_media_ids, true);

        } else {
            return ApiComponent::errorResponse([], 1000);
        }
    }

    public function actionShowTicket()
    {
        $request = ApiComponent::parseInputData();

        if (isset($request['ticket_id'])) {

            $tickets = (new Query())
                ->from('ticket')
                ->select(['ticket.*', 'user.username', 'auth_assignment.item_name as role'])
                ->leftJoin('user', 'user.id=ticket.user_id')
                ->leftJoin('auth_assignment', 'auth_assignment.user_id=user.id')
                ->where('ticket.id=' . $request['ticket_id'])
                ->orWhere('reply_to=' . $request['ticket_id'])
                ->orderBy('created_at ASC');
            $dataProvider = new ArrayDataProvider([
                'allModels' => $tickets->all(),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);
            $dataProvider->pagination->page = isset($request['page']) ? ($request['page'] - 1) : 0;

            $data = $dataProvider->getModels();
            $index = 0;
            foreach ($data as $ticket) {
                $ticket['created_at'] = Jdf::jdate('Y/m/d H:i', $ticket['created_at']);
                $data[$index++] = $ticket;
            }

            $page = $dataProvider->pagination->page + 1;
            $page_size = $dataProvider->pagination->pageSize;
            $pages = ceil($dataProvider->getTotalCount() / $page_size);

            return ApiComponent::successResponse('Ticket detail', [
                'data' => $data,
                'page' => $page,
                'page_size' => $page_size,
                'pages' => $pages
            ], true);

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    public function actionReply()
    {
        $request = ApiComponent::parseInputData();

        if (isset($request['ticket_id']) && isset($request['body'])) {

            $parent_ticket = Ticket::find()->where('id=' . $request['ticket_id'])->one();

            $ticket = new Ticket();
            $ticket->department = $parent_ticket->department;
            $ticket->deal_id = $parent_ticket->deal_id;
            $ticket->reply_to = $parent_ticket->id;
            $ticket->body = $request['body'];
            $ticket->created_at = time();
            $ticket->user_id = Yii::$app->user->id;
            $ticket->status = User::is_in_role(Yii::$app->user->id, 'customer') ? Ticket::CUSTOMER_REPLIED : Ticket::EXPERT_REPLIED;

            if ($ticket->save()) {

                if (!Yii::$app->user->isSuperadmin && User::is_in_role(Yii::$app->user->id, 'customer')) {
                    $parent_ticket->status = Ticket::NEED_EXPERT_REPLY;
                } else {
                    $parent_ticket->status = Ticket::NEED_CUSTOMER_REPLY;
                }

                $parent_ticket->save();

                Log::addLog(Log::ReplyTicket, $parent_ticket->id . '-' . $parent_ticket->status);

                if (User::is_in_role(Yii::$app->user->id, 'customer')) {
                    \app\controllers\TicketController::sendReplyToExpertsAndAdmins($parent_ticket->id, $parent_ticket->title, $ticket->body);
                } else {
                    \app\controllers\TicketController::sendReplyToCustomerAndAdmins($parent_ticket->id, $parent_ticket->title, $ticket->body);
                }
            }

            return ApiComponent::successResponse('Ticket replied successfully', $ticket, true);

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    public function actionCheck()
    {
        $request = ApiComponent::parseInputData();

        if (isset($request['ticket_id'])) {

            $ticket = Ticket::find()->where('id=' . $request['ticket_id'])->one();

            $ticket->updated_at = time();
            $ticket->status = Ticket::PENDING;
            if ($ticket->save()) {

                Log::addLog(Log::CheckTicket, $ticket->id . '-' . $ticket->status);
            }

            return ApiComponent::successResponse('Ticket is in checking mode', $ticket, true);

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    public function actionClose()
    {
        $request = ApiComponent::parseInputData();

        if (isset($request['ticket_id'])) {

            $ticket = Ticket::find()->where('id=' . $request['ticket_id'])->one();

            $ticket->updated_at = time();
            $ticket->status = Ticket::CLOSED;
            if ($ticket->save()) {

                Log::addLog(Log::CloseTicket, $ticket->id . '-' . $ticket->status);

                if (User::is_in_role(Yii::$app->user->id, 'customer')) {
                    \app\controllers\TicketController::sendReplyToExpertsAndAdmins($ticket->id, "تیکت ({$ticket->title}) توسط مشتری بسته شد", $ticket->body, "تیکت ({$ticket->title}) توسط مشتری بسته شد");
                } else {
                    \app\controllers\TicketController::sendReplyToCustomerAndAdmins($ticket->id, "تیکت ({$ticket->title}) توسط کارشناس بسته شد", $ticket->body, "تیکت ({$ticket->title}) توسط کارشناس بسته شد");
                }
            }

            return ApiComponent::successResponse('Ticket Closed successfully', $ticket, true);

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }
}