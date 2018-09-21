<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TicketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' موضوع تیکت: ' . $model->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-index">

    <div class="page-title">
        <div class="title_left">

        </div>
        <div class="title_right" style="width: 100%;text-align: left;">

            <p>
                <?= Html::a('تیکت ها', ['index'], ['class' => 'btn btn-success']) ?>
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

                    <?php
                    echo \yii\widgets\ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemView' => 'ticket_detail',
                    ]);
                    ?>

                    <hr>
                    <div class="panel panel-success" style="margin-top: 30px;">
                        <div class="panel-heading">
                            پاسخ
                        </div>
                        <?php $form = \yii\widgets\ActiveForm::begin(
                            [
                                'id' => 'ticket-form',
                                'options' => [
                                    'class' => 'form-horizontal form-label-left'
                                ]
                            ]
                        ); ?>

                            <div class="panel-body">

                                <?= $form->field($ticket, 'body')
                                    ->textarea(['rows' => 6])
                                    ->label('متن پاسخ')
                                ?>

                            </div>
                            <div class="panel-footer">
                                <div class="form-group">
                                    <?= Html::submitButton('ذخیره', ['class' => 'btn btn-success']) ?>
                                </div>
                            </div>
                            <?php \yii\widgets\ActiveForm::end(); ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
