<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Customer */
/* @var $form yii\widgets\ActiveForm */
?>



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
        <label class="control-label col-md-2" for="customer-image" style="padding-right: 0px;text-align: right;">تصویر</label>
        <div class="col-md-6">
            <input type="hidden" name="Customer[image]" value="">
            <input type="file" id="settings-logo" name="Customer[image]" value="" style="display: inline-block;">
        </div>
        <div class="col-md-4">
            <?php
            if(isset($model['image'])) {
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

    <?= $form->field($model, 'firstName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lastName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'companyName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'position')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'source')->widget(Select2::className(),
        ['data' => $model->get_all_sources(),
            'options' => ['dir' => 'rtl']])
    ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?php
        if(!$model->isNewRecord) {

            if ($model->status == 0) {
                echo $form->field($model, 'status')->dropDownList([
                        0 => 'سرنخ',
                        1 => 'مشتری'
                ])->label('تغییر وضعیت');

            } else if ($model->status == 1) {
                echo $form->field($model, 'status')->dropDownList([
                    1 => 'مشتری',
                    2 => 'معامله'
                ])->label('تغییر وضعیت');

            }
        }
    ?>

    <div class="form-group">
        <?= Html::submitButton('ذخیره', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
