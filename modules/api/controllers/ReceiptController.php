<?php

namespace app\modules\api\controllers;

use app\components\Jdf;
use app\models\Media;
use app\models\Receipt;
use app\models\ReceiptSearch;
use Yii;
use app\components\ApiComponent;
use app\models\Customer;
use app\models\CustomerSearch;
use app\models\Deal;
use app\models\DealSearch;
use app\models\Meeting;
use app\models\UserDeal;
use webvimark\modules\UserManagement\models\User;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class ReceiptController extends \yii\rest\Controller
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
            ],
        ];

        return $behaviors;
    }

    public function actionCustomerReceipts()
    {
        $searchModel = new ReceiptSearch();
        $params = Yii::$app->request->queryParams;
        $params['ReceiptSearch']['user_id'] = Yii::$app->user->id . '';
        $dataProvider = $searchModel->search($params);

        $data = $dataProvider->getModels();
        $index = 0;
        foreach ($data as $receipt) {
            $receipt['bank_id'] = Receipt::banks($receipt['bank_id']);
            $receipt['created_at'] = Jdf::jdate('Y/m/d H:i', $receipt['created_at']);
            $data[$index++] = $receipt;
        }

        $page = $dataProvider->pagination->page + 1;
        $page_size = $dataProvider->pagination->pageSize;
        $pages = ceil($dataProvider->getTotalCount() / $page_size);

        return ApiComponent::successResponse('Customer deals list', [
            'data' => $data,
            'page' => $page,
            'page_size' => $page_size,
            'pages' => $pages
        ], true);

    }

    public function actionGetBanks()
    {
        return ApiComponent::successResponse('', Receipt::banks(), true);
    }

    public function actionNew()
    {
        $request = Yii::$app->request->post();

        if (isset($request['bank_id']) && isset($request['amount']) && isset($request['receipt_number'])) {

            $model = new Receipt();
            $model->bank_id = $request['bank_id'];
            $model->user_id = \Yii::$app->user->id;
            $model->amount = $request['amount'];
            $model->receipt_number = $request['receipt_number'];
            $model->description = isset($request['description']) ? $request['description'] : "";
            $model->created_at = time();

            $transaction = Yii::$app->getDb()->beginTransaction();
            $dbSuccess = true;

            if (!$model->save()) {
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
                            move_uploaded_file($file_tmp, 'media/receipts/' . $file_name);

                            $path = \Yii::getAlias('@web') . '/media/receipts/' . $file_name;

                            $media = new Media();
                            $media->type = Media::$RECEIPT;
                            $media->meeting_id = $model->id;
                            $media->filename = $file_name;
                            $media->created_at = time();
                            $media->save();
                        }
                    }
                }

                $transaction->commit();
                return ApiComponent::successResponse('Receipt saved successfully', $model, true);

            } else {
                $transaction->rollBack();
                return ApiComponent::errorResponse([], 500);
            }

        } else {
            return ApiComponent::errorResponse([], 1000);

        }
    }
}