<?php

namespace app\modules\api\controllers;

use app\components\ApiComponent;

class UserController extends \yii\rest\Controller
{
    public function beforeAction($action)
    {
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    public function actionIndex() {
        $result = [];

        ApiComponent::successResponse('msg', 'data');
    }

}