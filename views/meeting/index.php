<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MeetingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'پرونده مشتری: ' . $customer->firstName . ' ' . $customer->lastName;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="meeting-index">

    <div class="page-title">
        <div class="title_left">

        </div>
        <div class="title_right" style="width: 100%;text-align: left;">

            <a href="create?customer_id=<?=$_GET['customer_id']?>" class="btn btn-success">ثبت جلسه</a>
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

                    <div class="row customer-doc">
                        <div class="col-md-4 doc-right">

                            <div class="customer-image">
                                <?php
                                    if(!isset($customer->image)) {
                                        ?>
                                        <img src="<?= Yii::$app->homeUrl ?>images/no_image.png"
                                             class="img-responsive img-circle">
                                        <?php
                                    } else {
                                        ?>
                                        <img src="<?= Yii::$app->homeUrl ?>Uploads/<?= $customer->image ?>"
                                             class="img-responsive img-circle">
                                        <?php
                                    }
                                ?>
                            </div>

                            <div class="customer-info">
                                <p><?php echo $customer->firstName . ' ' . $customer->lastName ?></p>
                                <p>کد: <?php echo $customer->id ?></p>
                                <p>سمت: <?php echo $customer->position ?></p>
                                <p>شرکت: <?php echo $customer->companyName ?></p>
                                <p>موبایل: <?php echo $customer->mobile ?></p>
                            </div>

                            <button class="btn btn-link more-btn" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                بیشتر...
                            </button>
                            <div class="collapse" style="clear: both;" id="collapseExample">

                                <div class="customer-info">
                                    <p>تلفن: <?php echo $customer->phone ?></p>
                                    <p>منبع: <?php echo $customer->source ?></p>
                                    <p>تاریخ ثبت: <?= \app\components\Jdf::jdate('Y/m/d', $customer->created_at) ?></p>
                                </div>
                            </div>

                            <div class="more-box-title">توضیحات</div>
                            <div class="more-box">
                                <?php echo $customer->description ?>
                            </div>


                        </div>
                        <div class="col-md-8 doc-left">

                            <?php
                            echo \yii\widgets\ListView::widget( [
                                'dataProvider' => $dataProvider,
                                'itemView' => 'customer_meeting_item',
                            ] );
                            ?>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
