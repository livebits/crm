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
            ->select(['project.id as id', 'project.title as title'])
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

    <?= $form->field($model, 'project_id')
        ->widget(\kartik\select2\Select2::className(), ['data' => $projects,  'options' => ['dir' => 'rtl', 'placeholder' => 'پروژه مورد نظر را انتخاب کنید']]) ?>

    <?= $form->field($model, 'publish_version')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'package_name')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'sign_file')->fileInput([]) ?>.
    <label>فایل Sign</label>
    <?= \kartik\file\FileInput::widget([
        'name' => 'ProjectInfo[sign_file]',
        'id' => 'projectinfo-sign_file',
        'options'=>[
            'multiple'=>false,
        ],
        'pluginOptions' => [
            'overwriteInitial'=>true,
            'maxFileSize'=>10000,
//            'allowedFileExtensions'=>['jpg', 'png'],
            'showCaption' => false,
            'showRemove' => true,
            'showUpload' => false,
            'browseClass' => 'btn btn-success btn-sm',
            'removeClass' => 'btn btn-danger btn-sm',
            'maxFileCount' => 1,
        ]
    ]); ?>
    <?php
        if(!$model->isNewRecord) {
            if($model->sign_file) {
                ?>

                <div class="panel panel-info">
                    <!--                <div class="panel-heading"></div>-->
                    <div class="panel-body">
                        <a target="_blank"
                           href="<?= Yii::$app->homeUrl . 'media/project/attachments/' . $model->sign_file ?>">
                            <?= explode('_', $model->sign_file)[2] ?>
                        </a>
                    </div>
                </div>

                <?php
            }
        }
    ?>

<!--    --><?//= $form->field($model, 'keystore')->fileInput([]) ?>
    <label style="margin-top: 20px">رمز Keystore</label>
    <?= \kartik\file\FileInput::widget([
        'name' => 'ProjectInfo[keystore]',
        'id' => 'projectinfo-keystore',
        'options'=>[
            'multiple'=>false,
        ],
        'pluginOptions' => [
            'overwriteInitial'=>true,
            'maxFileSize'=>10000,
//            'allowedFileExtensions'=>['jpg', 'png'],
            'showCaption' => false,
            'showRemove' => true,
            'showUpload' => false,
            'browseClass' => 'btn btn-success btn-sm',
            'removeClass' => 'btn btn-danger btn-sm',
            'maxFileCount' => 1,
        ]
    ]); ?>
    <?php
    if(!$model->isNewRecord) {
        if($model->keystore) {
            ?>

            <div class="panel panel-info">
                <!--                <div class="panel-heading"></div>-->
                <div class="panel-body">
                    <a target="_blank"
                       href="<?= Yii::$app->homeUrl . 'media/project/attachments/' . $model->keystore ?>">
                        <?= explode('_', $model->keystore)[2] ?>
                    </a>
                </div>
            </div>

            <?php
        }
    }
    ?>

    <?= $form->field($model, 'api_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'key_alias_password')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('ذخیره', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
