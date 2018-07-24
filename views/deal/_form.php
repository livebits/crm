<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\jDateTimePicker\JDTPicker;

/* @var $this yii\web\View */
/* @var $model app\models\Deal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="deal-form">

    <?php
        $customer = \app\models\Customer::find()
        ->where('id=' . $_GET['customer_id'])->one();

        $levels = $model->get_all_deal_levels();
    ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'customer_id')->textInput([
        'readonly' => true,
        'placeholder' => $customer->firstName . ' ' . $customer->lastName
    ]) ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'level')->dropDownList(
        $levels
    );
    ?>

    <?= $form->field($model, 'created_at')->widget(JDTPicker::className(), [
        'clientOptions' => [
            'EnableTimePicker' => false
        ]
    ])->textInput(['placeholder' => '', 'value' => \app\components\Jdf::jdate('Y/m/d', $model->created_at)]) ?>

    <div class="form-group">
        <?= Html::submitButton('ذخیره', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
