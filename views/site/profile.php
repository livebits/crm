<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Customer */
/* @var $form yii\widgets\ActiveForm */

$this->title = "ویرایش پروفایل کاربر";
?>

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

                <div class="col-md-6">

                    <?php $form = ActiveForm::begin(
                        [
                            'options' => [
                                'class' => 'form-horizontal form-label-left',
                                'enctype' => 'multipart/form-data'
                            ]
                        ]
                    ); ?>

                    <div class="form-group field-image">
                        <label class="control-label col-md-2" for="UserProfile-image" style="padding-right: 0px;text-align: right;">تصویر</label>
                        <div class="col-md-6">
                            <input type="hidden" name="UserProfile[image]" value="">
                            <input type="file" id="UserProfile-logo" name="UserProfile[image]" value="" style="display: inline-block;">
                        </div>
                        <div class="col-md-4">
                            <?php
                            if(isset($model['image']) && $model['image'] != "") {
                                ?>
                                <img class="img-responsive img-circle" src="<?= Yii::$app->homeUrl ?>Uploads/<?= $model['image'] ?>"
                                     style="width: 100px">
                                <?php
                            } else {
                                ?>
                                <img class="img-responsive img-circle" src="<?= Yii::$app->homeUrl ?>images/no_image.png"
                                     style="width: 100px">
                                <?php
                            }
                            ?>
                        </div>
                        <div class="col-md-offset-2 col-md-10"></div>
                        <div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div>
                    </div>

                    <?= $form->field($model, 'user_id')
                        ->hiddenInput(['value' => Yii::$app->user->id])->label('') ?>

                    <?= $form->field($model, 'firstName')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'lastName')->textInput(['maxlength' => true]) ?>

                    <div class="form-group">
                        <?= Html::submitButton('ذخیره', ['class' => 'btn btn-success']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>

            </div>
        </div>
    </div>
</div>

