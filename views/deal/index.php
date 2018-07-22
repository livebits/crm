<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DealSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$customer = \app\models\Customer::findOne($_GET['customer_id']);
$this->title = 'معاملات مشتری: ' . $customer->firstName . ' ' . $customer->lastName;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deal-index">

    <div class="page-title">
        <div class="title_left">

        </div>
        <div class="title_right" style="width: 100%;text-align: left;">
            <?= Html::a('ایجاد معامله', ['create?customer_id='.$_GET['customer_id']], ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br/>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            [
                                'attribute' => 'customer_id',
                                'label' => 'نام مشتری',
                                'value' => function($model) {
                                    return $model->firstName . ' ' . $model->lastName;
                                }
                            ],
                            'subject',
                            'price',
                            [
//                'attribute' => 'customer_id',
                                'label' => 'تلفن',
                                'value' => function($model) {
                                    $customer = \app\models\Customer::find($model->customer_id)->one();
                                    return $customer->mobile;
                                }
                            ],
                            [
                                'label' => 'آخرین مذاکره',
                                'value' => function($model) {
                                    if(isset($model->latestMeeting)) {
                                        return \app\components\Jdf::jdate('Y/m/d', $model->latestMeeting);
                                    } else {
                                        return '';
                                    }
                                }
                            ],
                            [
                                'label' => 'تاریخ پیگیری بعدی',
                                'value' => function($model) {
                                    if(isset($model->nextMeeting)) {
                                        return \app\components\Jdf::jdate('Y/m/d', $model->nextMeeting);
                                    } else {
                                        return '';
                                    }
                                }
                            ],
                            [
                                'attribute' => 'level',
                                'label' => 'مرحله',
                                'value' => function($model) {

                                    return$model->levelName;
                                },
                            ],
                            //'created_at',
                            //'updated_at',

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view} {update} {meeting}',
                                'header' => 'عملیات',
                                'buttons' => [
                                    'view' => function($url, $model, $id){

                                        $url = Yii::$app->urlManager->createUrl([
                                            'deal/view',
                                            'id' => $model->id,
                                            'customer_id' => $model->customer_id,
                                        ]);

                                        return '<a href="' . $url . '" class="fa fa-eye" title="مشاهده"></a>';
                                    },
                                    'update' => function($url, $model, $id){

                                        $url = Yii::$app->urlManager->createUrl([
                                            'deal/update',
                                            'id' => $model->id,
                                            'customer_id' => $model->customer_id,
                                        ]);

                                        return '<a href="' . $url . '" class="fa fa-pencil" title="ویرایش"></a>';
                                    },
                                    'meeting' => function($url, $model, $id){

                                        $url = Yii::$app->urlManager->createUrl([
                                            'meeting/deal-index',
                                            'deal_id' => $model->id,
                                            'customer_id' => $model->customer_id,
                                        ]);

                                        return '<a href="' . $url . '" class="fa fa-comments" title="جلسات"></a>';
                                    },
                                ]
                            ],
                        ],
                    ]); ?>

                </div>
            </div>
        </div>
    </div>
</div>
