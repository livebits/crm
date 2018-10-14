<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectInfoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-info-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'project_id') ?>

    <?= $form->field($model, 'publish_version') ?>

    <?= $form->field($model, 'package_name') ?>

    <?= $form->field($model, 'sign_file') ?>

    <?php // echo $form->field($model, 'keystore') ?>

    <?php // echo $form->field($model, 'api_key') ?>

    <?php // echo $form->field($model, 'key_alias_password') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
