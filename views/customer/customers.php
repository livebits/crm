<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'مشتری';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-index">

    <!--    --><?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="page-title">
        <div class="title_left">

        </div>
        <div class="title_right" style="width: 100%;text-align: left;">

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
                                'attribute' => 'firstName',
                                'label' => 'نام مشتری',
                                'value' => function($model) {
                                    return $model->firstName . ' ' . $model->lastName;
                                }
                            ],
                            [
                                'format' => 'raw',
                                'label' => 'احتمال قرارداد',
                            ],
                            [
                                'format' => 'raw',
                                'label' => 'اطلاعات تماس',
                                'value' => function($model) {
                                    return $model->mobile . "<br>" . $model->phone;
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
                                'format' => 'raw',
                                'label' => 'موضوع درفت قرارداد'
                            ],
                            [
                                'format' => 'raw',
                                'label' => 'قیمت قرارداد'
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view} {update} {delete} {meeting}',
                                'header' => 'عملیات',
                                'buttons' => [
                                    'meeting' => function($url, $model, $id){

                                        $url = Yii::$app->urlManager->createUrl([
                                            'meeting/index',
                                            'customer_id' => $model->id,
                                        ]);

                                        return '<a href="' . $url . '" class="fa fa-comments" title="جلسات"></a>';
                                    },
                                ]
                            ]
                        ],
                    ]); ?>

                </div>
            </div>
        </div>
    </div>
</div>
