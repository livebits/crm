<?php

namespace app\modules\api\controllers;

use app\components\ApiComponent;
use app\models\SourceSearch;
use app\models\Task;
use yii\data\ArrayDataProvider;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class TaskController extends \yii\rest\Controller
{

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

    /**
     * @api {post} /task/get-customer-tasks 15- get customer tasks
     * @apiName 15.get customer tasks
     * @apiGroup Task
     *
     * @apiParam {String} customer_id customer id.
     *
     * @apiParamExample {json} Request-Example:
     *      {
     *         "customer_id":"2"
     *      }
     *
     * @apiSuccess {Array} data response data.
     * @apiSuccess {String} data.id task id.
     * @apiSuccess {String} data.name task name.
     * @apiSuccess {String} data.is_done task done status (0: not done, 1: done).
     * @apiSuccess {String} message response message.
     * @apiSuccess {Integer} code response code [0: failure, 1: success].
     * @apiSuccess {Integer} status response status code [see -status table-].
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       {
     *           "data": [
     *               {
     *                   "id": "19",
     *                   "name": "task1",
     *                   "is_done": "0"
     *               },
     *               {
     *                   "id": "12",
     *                   "name": "task2",
     *                   "is_done": "1"
     *               },
     *               {
     *                   "id": "11",
     *                   "name": "task3",
     *                   "is_done": "1"
     *               }
     *           ],
     *           "message": "",
     *           "code": 1,
     *           "status": 200
     *       }
     *
     *
     * @apiError EnterRequiredInputs
     * @apiErrorExample Error-Response 1000:
     *     HTTP/1.1 400 Bad request
     *     {
     *         "data": [],
     *         "message": "Enter required data",
     *         "code": 0,
     *         "status": 1000
     *     }
     *
     */
    public function actionGetCustomerTasks()
    {
        $request = ApiComponent::parseInputData();

        if (isset($request['customer_id'])) {
            $tasks = Task::find()
                ->select(['id', 'name', 'is_done'])
                ->where('customer_id=' . $request['customer_id'])
                ->orderBy('created_at DESC')
                ->asArray()
                ->all();

            return ApiComponent::successResponse('', $tasks, true);

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    /**
     * @api {post} /task/get-deal-tasks 16- get deal tasks
     * @apiName 16.get deal tasks
     * @apiGroup Task
     *
     * @apiParam {String} deal_id deal id.
     *
     * @apiParamExample {json} Request-Example:
     *      {
     *         "deal_id":"2"
     *      }
     *
     * @apiSuccess {Array} data response data.
     * @apiSuccess {String} data.id task id.
     * @apiSuccess {String} data.name task name.
     * @apiSuccess {String} data.is_done task done status (0: not done, 1: done).
     * @apiSuccess {String} message response message.
     * @apiSuccess {Integer} code response code [0: failure, 1: success].
     * @apiSuccess {Integer} status response status code [see -status table-].
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       {
     *           "data": [
     *               {
     *                   "id": "5",
     *                   "name": "task1",
     *                   "is_done": "0"
     *               },
     *               {
     *                   "id": "6",
     *                   "name": "task2",
     *                   "is_done": "1"
     *               }
     *           ],
     *           "message": "",
     *           "code": 1,
     *           "status": 200
     *       }
     *
     *
     * @apiError EnterRequiredInputs
     * @apiErrorExample Error-Response 1000:
     *     HTTP/1.1 400 Bad request
     *     {
     *         "data": [],
     *         "message": "Enter required data",
     *         "code": 0,
     *         "status": 1000
     *     }
     *
     */
    public function actionGetDealTasks()
    {
        $request = ApiComponent::parseInputData();

        if (isset($request['deal_id'])) {
            $tasks = Task::find()
                ->select(['id', 'name', 'is_done'])
                ->where('deal_id=' . $request['deal_id'])
                ->orderBy('created_at DESC')
                ->asArray()
                ->all();

            return ApiComponent::successResponse('', $tasks, true);

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }


    /**
     * @api {post} /task/add-customer-task 17- add customer task
     * @apiName 17.add customer task
     * @apiGroup Task
     *
     * @apiParam {String} customer_id customer id.
     * @apiParam {String} task_name task name.
     *
     * @apiParamExample {json} Request-Example:
     *      {
     *         "customer_id":"2"
     *         "task_name":"add to board"
     *      }
     *
     * @apiSuccess {Array} data response data.
     * @apiSuccess {String} data.id task id.
     * @apiSuccess {String} data.name task name.
     * @apiSuccess {String} data.is_done task done status (0: not done, 1: done).
     * @apiSuccess {String} message response message.
     * @apiSuccess {Integer} code response code [0: failure, 1: success].
     * @apiSuccess {Integer} status response status code [see -status table-].
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       {
     *           "data": [
     *               {
     *                   "id": "23",
     *                   "name": "add to board",
     *                   "is_done": "0"
     *               }
     *           ],
     *           "message": "",
     *           "code": 1,
     *           "status": 200
     *       }
     *
     * @apiError EnterRequiredInputs
     * @apiErrorExample Error-Response 1000:
     *     HTTP/1.1 400 Bad request
     *     {
     *         "data": [],
     *         "message": "Enter required data",
     *         "code": 0,
     *         "status": 1000
     *     }
     *
     */
    public function actionAddCustomerTask()
    {
        $request = ApiComponent::parseInputData();

        if (isset($request['task_name']) && isset($request['customer_id'])) {

            $model = new Task();
            $model->customer_id = $request['customer_id'];
            $model->name = $request['task_name'];
            $model->created_at = time();
            $model->is_done = 0;
            $model->save(false);

            $tasks = Task::find()
                ->select(['id', 'name', 'is_done'])
                ->where('customer_id=' . $request['customer_id'])
                ->orderBy('created_at DESC')
                ->asArray()
                ->all();

            return ApiComponent::successResponse('', $tasks, true);

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    /**
     * @api {post} /task/add-deal-task 18- add deal task
     * @apiName 17.add deal task
     * @apiGroup Task
     *
     * @apiParam {String} deal_id deal id.
     * @apiParam {String} task_name task name.
     *
     * @apiParamExample {json} Request-Example:
     *      {
     *         "deal_id":"2"
     *         "task_name":"add to board"
     *      }
     *
     * @apiSuccess {Array} data response data.
     * @apiSuccess {String} data.id task id.
     * @apiSuccess {String} data.name task name.
     * @apiSuccess {String} data.is_done task done status (0: not done, 1: done).
     * @apiSuccess {String} message response message.
     * @apiSuccess {Integer} code response code [0: failure, 1: success].
     * @apiSuccess {Integer} status response status code [see -status table-].
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       {
     *           "data": [
     *               {
     *                   "id": "23",
     *                   "name": "add to board",
     *                   "is_done": "0"
     *               }
     *           ],
     *           "message": "",
     *           "code": 1,
     *           "status": 200
     *       }
     *
     * @apiError EnterRequiredInputs
     * @apiErrorExample Error-Response 1000:
     *     HTTP/1.1 400 Bad request
     *     {
     *         "data": [],
     *         "message": "Enter required data",
     *         "code": 0,
     *         "status": 1000
     *     }
     *
     */
    public function actionAddDealTask()
    {
        $request = ApiComponent::parseInputData();

        if (isset($request['task_name']) && isset($request['deal_id'])) {

            $model = new Task();
            $model->deal_id = $request['deal_id'];
            $model->name = $request['task_name'];
            $model->created_at = time();
            $model->is_done = 0;
            $model->save(false);

            $tasks = Task::find()
                ->select(['id', 'name', 'is_done'])
                ->where('deal_id=' . $request['deal_id'])
                ->orderBy('created_at DESC')
                ->asArray()
                ->all();

            return ApiComponent::successResponse('', $tasks, true);

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    /**
     * @api {post} /task/delete-task 18- delete task
     * @apiName 18.delete task
     * @apiGroup Task
     *
     * @apiParam {String} task_id task id.
     *
     * @apiParamExample {json} Request-Example:
     *      {
     *         "task_id":"22"
     *      }
     *
     * @apiSuccess {Array} data response data.
     * @apiSuccess {String} message response message.
     * @apiSuccess {Integer} code response code [0: failure, 1: success].
     * @apiSuccess {Integer} status response status code [see -status table-].
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       {
     *           "data": [],
     *           "message": "Task delete successfully",
     *           "code": 1,
     *           "status": 200
     *       }
     *
     * @apiError EnterRequiredInputs
     * @apiErrorExample Error-Response 1000:
     *     HTTP/1.1 400 Bad request
     *     {
     *         "data": [],
     *         "message": "Enter required data",
     *         "code": 0,
     *         "status": 1000
     *     }
     *
     * @apiError ItemNotFound
     * @apiErrorExample Error-Response 1002:
     *     HTTP/1.1 400 Bad request
     *     {
     *         "data": [],
     *         "message": "item not found",
     *         "code": 0,
     *         "status": 1002
     *     }
     *
     */
    public function actionDeleteTask()
    {
        $request = ApiComponent::parseInputData();

        if (isset($request['task_id'])) {

            $task = Task::findOne($request['task_id']);
            if($task) {
                $task->delete();
            } else {
                return ApiComponent::errorResponse([], 1002);
            }

            $message = "Task delete successfully";
            return ApiComponent::successResponse($message, [], true);

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    /**
     * @api {post} /task/update-customer-tasks 19- update customer tasks
     * @apiName 19.update customer tasks
     * @apiGroup Task
     *
     * @apiParam {String} customer_id customer id.
     * @apiParam {Array} tasks task to updated.
     * @apiParam {integer} tasks.id task id.
     * @apiParam {integer} tasks.status task new status [0:undone, 1:done].
     *
     * @apiParamExample {json} Request-Example:
     *      {
     *        "customer_id":"5",
     *        "tasks":[
     *            {
     *               "id":3,
     *               "status": 0
     *            },
     *            {
     *               "id":4,
     *               "status": 1
     *            }
     *         ]
     *     }
     *
     *
     * @apiSuccess {Array} data response data.
     * @apiSuccess {String} data.id task id.
     * @apiSuccess {String} data.name task name.
     * @apiSuccess {String} data.is_done task done status.
     * @apiSuccess {String} message response message.
     * @apiSuccess {Integer} code response code [0: failure, 1: success].
     * @apiSuccess {Integer} status response status code [see -status table-].
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       {
     *           "data": [
     *               {
     *                   "id": "3",
     *                   "name": "t1",
     *                   "is_done": "0"
     *               },
     *               {
     *                   "id": "4",
     *                   "name": "t2",
     *                   "is_done": "1"
     *               }
     *           ],
     *           "message": "",
     *           "code": 1,
     *           "status": 200
     *       }
     *
     * @apiError EnterRequiredInputs
     * @apiErrorExample Error-Response 1000:
     *     HTTP/1.1 400 Bad request
     *     {
     *         "data": [],
     *         "message": "Enter required data",
     *         "code": 0,
     *         "status": 1000
     *     }
     *
     */
    public function actionUpdateCustomerTasks() {
        $request = ApiComponent::parseInputData();

        if (isset($request['customer_id']) && isset($request['tasks'])) {

            $tasks = $request['tasks'];
            foreach ($tasks as $task) {
                Task::updateAll(['is_done' => $this->getStatus($task['status'])], 'id='.$task['id']);
            }

            $tasks = Task::find()
                ->select(['id', 'name', 'is_done'])
                ->where('customer_id=' . $request['customer_id'])
                ->orderBy('created_at DESC')
                ->asArray()
                ->all();

            return ApiComponent::successResponse('', $tasks, true);

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }


    /**
     * @api {post} /task/update-deal-tasks 20- update deal tasks
     * @apiName 20.update deal tasks
     * @apiGroup Task
     *
     * @apiParam {String} deal_id deal id.
     * @apiParam {Array} tasks task to updated.
     * @apiParam {integer} tasks.id task id.
     * @apiParam {integer} tasks.status task new status [0:undone, 1:done].
     *
     * @apiParamExample {json} Request-Example:
     *      {
     *        "deal_id":"5",
     *        "tasks":[
     *            {
     *               "id":3,
     *               "status": 0
     *            },
     *            {
     *               "id":4,
     *               "status": 1
     *            }
     *         ]
     *     }
     *
     *
     * @apiSuccess {Array} data response data.
     * @apiSuccess {String} data.id task id.
     * @apiSuccess {String} data.name task name.
     * @apiSuccess {String} data.is_done task done status.
     * @apiSuccess {String} message response message.
     * @apiSuccess {Integer} code response code [0: failure, 1: success].
     * @apiSuccess {Integer} status response status code [see -status table-].
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       {
     *           "data": [
     *               {
     *                   "id": "3",
     *                   "name": "t1",
     *                   "is_done": "0"
     *               },
     *               {
     *                   "id": "4",
     *                   "name": "t2",
     *                   "is_done": "1"
     *               }
     *           ],
     *           "message": "",
     *           "code": 1,
     *           "status": 200
     *       }
     *
     * @apiError EnterRequiredInputs
     * @apiErrorExample Error-Response 1000:
     *     HTTP/1.1 400 Bad request
     *     {
     *         "data": [],
     *         "message": "Enter required data",
     *         "code": 0,
     *         "status": 1000
     *     }
     *
     */
    public function actionUpdateDealTasks() {
        $request = ApiComponent::parseInputData();

        if (isset($request['deal_id']) && isset($request['tasks'])) {

            $tasks = $request['tasks'];
            foreach ($tasks as $task) {
                Task::updateAll(['is_done' => $this->getStatus($task['status'])], 'id='.$task['id']);
            }

            $tasks = Task::find()
                ->select(['id', 'name', 'is_done'])
                ->where('deal_id=' . $request['deal_id'])
                ->orderBy('created_at DESC')
                ->asArray()
                ->all();

            return ApiComponent::successResponse('', $tasks, true);

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }

    public function getStatus($value) {

        if($value == null || $value == "" || $value == 0) {
            return 0;
        } else {
            return 1;
        }
    }
}