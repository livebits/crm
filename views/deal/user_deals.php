<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DealSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'قراردادها';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deal-index">

    <?php  echo $this->render('_search', ['searchModel' => $searchModel]); ?>

    <div class="page-title">
        <div class="title_left">

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
                            'subject',
                            [
                                'label' => 'تاریخ قرارداد',
                                'value' => function($model) {
                                    if(isset($model->created_at)) {
                                        return \app\components\Jdf::jdate('Y/m/d', $model->created_at);
                                    } else {
                                        return '';
                                    }
                                }
                            ],
                            [
                                'attribute' => 'level',
                                'label' => 'مرحله',
                                'value' => function($model) {

                                    return $model->levelName;
                                },
                            ],
                            [
                                'attribute' => 'level',
                                'label' => 'درصد پیشرفت',
                                'value' => function($model) {

                                    return '';
                                },
                            ],
//                            [
//                                'class' => 'yii\grid\ActionColumn',
//                                'template' => '',
//                                'header' => 'عملیات',
//                                'buttons' => [
//                                ]
//                            ],
                        ],
                    ]); ?>

                </div>
            </div>
        </div>
    </div>
</div>
