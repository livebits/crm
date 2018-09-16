<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TicketSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ticket-search">

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
                    <h2>جستجو</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    <?php $form = ActiveForm::begin([
                        'action' => ['index'],
                        'method' => 'get',
                    ]); ?>

                    <div class="col-md-4">
                        <?= $form->field($model, 'deal_id') ?>
                    </div>

                    <div class="col-md-4">
                        <?= $form->field($model, 'department') ?>
                    </div>

                    <div class="col-md-4">
                        <?= $form->field($model, 'title') ?>
                    </div>

                    <?php // echo $form->field($model, 'created_at') ?>

                    <?php // echo $form->field($model, 'updated_at') ?>

                    <div class="form-group">
                        <?= Html::submitButton('جستجو', ['class' => 'btn btn-primary']) ?>
                        <?= Html::resetButton('پاک کردن فیلترها', ['class' => 'btn btn-default']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>

</div>
