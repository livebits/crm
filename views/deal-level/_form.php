<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DealLevel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="deal-level-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'level_number')->textInput() ?>

    <?= $form->field($model, 'level_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('ذخیره', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
