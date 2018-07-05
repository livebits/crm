<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Meeting */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Meetings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="meeting-view">

    <div class="page-title">
        <div class="title_left">

        </div>
        <div class="title_right" style="width: 100%;text-align: left;">

            <?php
            $url = Yii::$app->urlManager->createUrl([
            'meeting/index',
            'customer_id' => $_GET['customer_id'],
            ]);

            echo '<a href="' . $url . '" class="btn btn-info" >لیست جلسات مشتری</a>';
            ?>
            <?= Html::a('بروز رسانی', ['update', 'id' => $model->id, 'customer_id' => $_GET['customer_id']], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('حذف', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'مورد انتخاب شده حذف شود؟',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>ویرایش جلسه: <?= Html::encode($this->title) ?></h2>
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
                            'content:ntext',
                            [
                                "attribute" => 'created_at',
                                "value" => function($model) {
                                    return \app\components\Jdf::jdate("Y/m/d", $model->created_at);
                                }
                            ],
                            [
                                "attribute" => 'next_date',
                                "value" => function($model) {
                                    return \app\components\Jdf::jdate("Y/m/d", $model->next_date);
                                }
                            ],
                            [
                                "attribute" => 'rating',
                                "format" => "raw",
                                "value" => function($model) {
                                    $rate = $model->rating;
                                    $text = "";
                                    for($i=0; $i<$rate; $i++) {
                                        $text .= "<span class='fa fa-star'></span>";
                                    }

                                    return $text;
                                }
                            ],
                        ],
                    ]) ?>

                    <?php
                    $media = \app\models\Media::find()
                        ->where('meeting_id=' . $model->id)
                        ->all();

                    ?>
                    <div class="panel panel-info">
                        <div class="panel-heading">تصاویر</div>
                        <div class="panel-body">

                            <div class="gallery">
                            <?php
                            foreach ($media as $media_file) {

                                if($media_file->type != \app\models\Media::$IMAGE){
                                    continue;
                                }

                                echo '<div class="gallery-item"><a target="_blank" href="'. Yii::$app->homeUrl . 'media/images/' . $media_file->filename . '">' .
                                     '<img src="' . Yii::$app->homeUrl . 'media/images/' . $media_file->filename . '" class="myimg-responsive"></a></div>';
                            }
                            ?>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-info">
                        <div class="panel-heading">صوت</div>
                        <div class="panel-body">
                            <table>

                                <?php
                                foreach ($media as $media_file) {

                                    if($media_file->type != \app\models\Media::$AUDIO){
                                        continue;
                                    }
                                    echo '<tr style="border-bottom: 1px solid #c2c2c2;"><td><a target="_blank" href="'. Yii::$app->homeUrl . 'media/audio/' . $media_file->filename . '">' . explode('_', $media_file->filename)[1] . '</a></td></tr>';
                                }
                                ?>
                            </table>
                        </div>
                    </div>

                    <div class="panel panel-info">
                        <div class="panel-heading">ضمیمه</div>
                        <div class="panel-body">
                            <table>

                                <?php
                                foreach ($media as $media_file) {

                                    if($media_file->type != \app\models\Media::$OTHER){
                                        continue;
                                    }
                                    echo '<tr style="border-bottom: 1px solid #c2c2c2;"><td><a target="_blank" href="'. Yii::$app->homeUrl . 'media/other/' . $media_file->filename . '">' . explode('_', $media_file->filename)[1] . '</a></td></tr>';
                                }
                                ?>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
