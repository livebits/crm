<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ReceiptSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'رسیدهای پرداخت شده';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="ticket-index">

    <div class="page-title">
        <div class="title_left">

        </div>
        <div class="title_right" style="width: 100%;text-align: left;">

            <p>
                <?= Html::a('ثبت فیش', ['create'], ['class' => 'btn btn-success']) ?>
            </p>

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

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'id',
//            'user_id',
                            [
                                "attribute" => 'bank_id',
                                "value" => function($model) {
                                    return \app\models\Receipt::banks($model->bank_id);
                                }
                            ],
                            'amount',
                            'receipt_number',
                            'description',
                            [
                                "attribute" => 'created_at',
                                "value" => function($model) {
                                    return \app\components\Jdf::jdate("Y/m/d", $model->created_at);
                                }
                            ],
                            //'updated_at',

                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>

                </div>
            </div>
        </div>
    </div>
</div>