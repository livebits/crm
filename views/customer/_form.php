<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Customer */
/* @var $form yii\widgets\ActiveForm */
?>



<div class="col-md-6">

    <?php $form = ActiveForm::begin(
            [
                'options' => [
                    'class' => 'form-horizontal form-label-left'
                ]
            ]
    ); ?>

    <?= $form->field($model, 'firstName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lastName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'companyName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'position')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'source')->textInput(['maxlength' => true]) ?>

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
