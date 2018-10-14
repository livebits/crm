<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectInfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-info-form">

    <?php
    $projects = \yii\helpers\ArrayHelper::map(
        (new \yii\db\Query())
            ->from('expert_project')
            ->select(['expert_project.id', 'project.title as title'])
            ->leftJoin('project', 'project.id=expert_project.project_id')
            ->where('expert_project.expert_id=' . Yii::$app->user->id)
            ->all(),
        'id', 'title');
    ?>

    <?php $form = ActiveForm::begin(
        [
            'id' => 'project-info-form',
            'options' => [
                'class' => 'form-horizontal form-label-left',
                'enctype' => 'multipart/form-data'
            ]
        ]
    ); ?>

    <?= $form->field($model, 'project_id')->dropDownList(
        $projects
    );
    ?>

    <?= $form->field($model, 'publish_version')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'package_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sign_file')->fileInput([]) ?>

    <?= $form->field($model, 'keystore')->fileInput([]) ?>

    <?= $form->field($model, 'api_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'key_alias_password')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('ذخیره', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
