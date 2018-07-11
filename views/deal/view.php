<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Deal */

$this->title = 'مشاهده معامله: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Deals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deal-view">


    <div class="page-title">
        <div class="title_left">

        </div>
        <div class="title_right" style="width: 100%;text-align: left;">
            <?php
            $url = Yii::$app->urlManager->createUrl([
                'deal/index',
                'customer_id' => $_GET['customer_id'],
            ]);

            echo '<a href="' . $url . '" class="btn btn-info" >لیست معاملات مشتری</a>';
            ?>
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

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            [
                                "attribute" => 'customer_id',
                                "value" => function($model) {
                                    $customer = \app\models\Customer::find()->where("id=" . $model->customer_id)->one();
                                    return $customer->firstName . ' ' . $customer->lastName;
                                }
                            ],
                            'subject',
                            [
                                "attribute" => 'price',
                                "value" => function($model) {
                                    return number_format($model->price);
                                }
                            ],
                            [
                                "attribute" => 'level',
                                "value" => function($model) {
                                    $arr = [
                                        "0" => "پیش پرداخت",
                                        "1" => "پیش نویس",
                                    ];
                                    return $arr[$model->level];
                                }
                            ],
                            [
                                "attribute" => 'created_at',
                                "value" => function($model) {
                                    return \app\components\Jdf::jdate("Y/m/d", $model->created_at);
                                }
                            ],
                        ],
                    ]) ?>

                </div>
            </div>
        </div>
    </div>

</div>
