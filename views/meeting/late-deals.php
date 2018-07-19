<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'جلسات عقب افتاده معاملات';
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
                                'attribute' => 'customer_code',
                                'label' => 'کد مشتری',
                                'value' => function($model) {
                                    return $model->customer_code;
                                }
                            ],
                            [
                                'attribute' => 'customer_name',
                                'label' => 'نام مشتری',
                                'value' => function($model) {
                                    return $model->customer_name;
                                }
                            ],
                            [
                                'attribute' => 'deal_subject',
                                'label' => 'موضوع قرارداد',
                                'value' => function($model) {
                                    return $model->deal_subject;
                                }
                            ],
                            [
                                'attribute' => 'next_date',
                                'label' => 'تاریخ جلسه عقب افتاده',
                                'value' => function($model) {
                                    if(isset($model->next_date)) {
                                        return \app\components\Jdf::jdate('Y/m/d', $model->next_date);
                                    } else {
                                        return '';
                                    }
                                }
                            ],
//                            'companyName',
                            //'position',
                            //'mobile',
                            //'phone',
                            //'description:ntext',
                            //'status',
                            //'createdAt',
                            //'updatedAt',

//                            ['class' => 'yii\grid\ActionColumn'],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{meeting}',
                                'header' => 'عملیات',
                                'buttons' => [
                                    'meeting' => function($url, $model, $id){

                                        $url = Yii::$app->urlManager->createUrl([
                                            'meeting/deal-index',
                                            'deal_id' => $model->deal_id,
                                        ]);

                                        return '<a href="' . $url . '" class="fa fa-comments" title="مشاهده پرونده"></a>';
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
