<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Ticket */

$this->title = 'مشاهده تیکت: '  . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Tickets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="ticket-view">

    <div class="page-title">
        <div class="title_left">

        </div>
        <div class="title_right" style="width: 100%;text-align: left;">


            <?= Html::a('تیکت ها', ['index'], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('بروز رسانی', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
                                "attribute" => 'deal_id',
                                "value" => function($model) {
                                    $deal = \app\models\Deal::find()->where("id=" . $model->deal_id)->one();
                                    return $deal->subject;
                                }
                            ],
                            [
                                "attribute" => 'department',
                                "value" => function($model) {
                                    $department = \app\models\Department::find()->where("id=" . $model->department)->one();
                                    return $department->name;
                                }
                            ],
                            'title',
                            'body:ntext',
                            [
                                "attribute" => 'created_at',
                                "value" => function($model) {
                                    return \app\components\Jdf::jdate("Y/m/d", $model->created_at);
                                }
                            ],
                            [
                                "attribute" => 'status',
                                "value" => function($model) {
                                    return \app\models\Ticket::ticketStatus($model->status);
                                }
                            ],
//                            'updated_at',
                        ],
                    ]) ?>

                    <?php
                    $media = \app\models\Media::find()
                        ->where('meeting_id=' . $model->id)
                        ->andWhere('type="TICKET_ATTACHMENT"')
                        ->all();

                    ?>

                    <div class="panel panel-info">
                        <div class="panel-heading">ضمیمه</div>
                        <div class="panel-body">
                            <table>

                                <?php
                                foreach ($media as $media_file) {

                                    echo '<tr style="border-bottom: 1px solid #c2c2c2;"><td><a target="_blank" href="'. Yii::$app->homeUrl . 'media/tickets/attachments/' . $media_file->filename . '">' . explode('_', $media_file->filename)[1] . '</a></td></tr>';
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