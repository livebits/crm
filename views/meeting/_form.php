<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\jDateTimePicker\JDTPicker;
use dosamigos\fileupload\FileUploadUI;

/* @var $this yii\web\View */
/* @var $model app\models\Meeting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="meeting-form">

    <?php $form = ActiveForm::begin([
        'id' => 'meeting-form'
    ]); ?>

    <input type="hidden" id="hiddenImageFiles" name="imageFiles" value="">
    <input type="hidden" id="hiddenAudioFiles" name="audioFiles" value="">
    <input type="hidden" id="hiddenOtherFiles" name="otherFiles" value="">

    <?= $form->field($model, 'user_id')->textInput(['readonly' => true, 'value' => '', 'placeholder' => $user['username']]) ?>

    <?= $form->field($model, 'customer_id')->textInput(['readonly' => true, 'value' => '', 'placeholder' => $customer->firstName . ' ' . $customer->lastName]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_at')->widget(JDTPicker::className(), [
        'clientOptions' => [
            'EnableTimePicker' => false
        ]
    ])->textInput(['placeholder' => '', 'value' => \app\components\Jdf::jdate('Y/m/d', $model->created_at)]) ?>

    <?= $form->field($model, 'next_date')->widget(JDTPicker::className(), [
        'clientOptions' => [
            'EnableTimePicker' => false
        ]
    ])->textInput(['placeholder' => '', 'value' => \app\components\Jdf::jdate('Y/m/d', $model->next_date)]) ?>

    <?= $form->field($model, 'rating')->dropDownList(
        [
            "1" => "خیلی کم",
            "2" => "کم",
            "3" => "متوسط",
            "4" => "زیاد",
            "5" => "خیلی زیاد",
        ],
        ['prompt' => 'لطفا میزان رضایت از جلسه را انتخاب کنید']
    );
    ?>

    <div class="panel panel-info img-responsive">
        <div class="panel-heading">
            تصاویر
        </div>
        <div class="panel-body">
            <?= FileUploadUI::widget([
                'model' => $imageMediaFile,
                'attribute' => 'imageFile',
                'url' => ['meeting/upload-images'],
                'gallery' => false,
                'fieldOptions' => [
                    'accept' => 'images/*'
                ],
                'clientOptions' => [
                    'maxFileSize' => 2000000
                ],
                'clientEvents' => [
                    'fileuploaddone' => 'function(e, data) {
                                image_ids.push(data._response.result.files[0].media_id);
                            }'
                ],
            ]); ?>

            <hr>
            <?php

            if (!$model->isNewRecord) {
                $media = \app\models\Media::find()
                    ->where('meeting_id=' . $model->id)
                    ->all();
                ?>
                <div class="gallery">
                    <?php
                    foreach ($media as $media_file) {

                        if ($media_file->type != \app\models\Media::$IMAGE) {
                            continue;
                        }

                        echo '<div class="gallery-item"><a target="_blank" href="' . Yii::$app->homeUrl . 'media/images/' . $media_file->filename . '">' .
                            '<img src="' . Yii::$app->homeUrl . 'media/images/' . $media_file->filename . '" class="myimg-responsive"></a>'.
                            '<div class="media-delete btn btn-danger" data-filename="' . $media_file->filename . '" data-media-id="' . $media_file->id . '" data-type="image">حذف</div> </div>';
                    }
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <div class="panel panel-info">
        <div class="panel-heading">
            صوت
        </div>
        <div class="panel-body">

            <?= FileUploadUI::widget([
                'model' => $soundMediaFile,
                'attribute' => 'audioFile',
                'url' => ['meeting/upload-sounds'],
                'id' => 'sound-upload',
                'gallery' => false,
                'fieldOptions' => [
                    'accept' => 'audio/*'
                ],
                'clientOptions' => [
                    'maxFileSize' => 2000000
                ],
                'clientEvents' => [
                    'fileuploaddone' => 'function(e, data) {
                                audio_ids.push(data._response.result.files[0].media_id);
                            }'
                ],
            ]); ?>

            <hr>
            <?php
            if (!$model->isNewRecord) {
                ?>
                <table>

                    <?php
                    foreach ($media as $media_file) {

                        if($media_file->type != \app\models\Media::$AUDIO){
                            continue;
                        }
                        echo '<tr style="border-bottom: 1px solid #c2c2c2;"><td><a target="_blank" href="'. Yii::$app->homeUrl . 'media/audio/' . $media_file->filename . '">' . explode('_', $media_file->filename)[1] . '</a></td></tr>';
                    }
                    ?>
                </table>
                <?php
            }
            ?>

        </div>
    </div>

    <div class="panel panel-info">
        <div class="panel-heading">
            ضمیمه
        </div>
        <div class="panel-body">

            <?= FileUploadUI::widget([
                'model' => $otherMediaFile,
                'attribute' => 'otherFile',
                'url' => ['meeting/upload-other'],
                'id' => 'other-upload',
                'gallery' => false,
                'fieldOptions' => [
                    'accept' => '*'
                ],
                'clientOptions' => [
                    'maxFileSize' => 2000000
                ],
                'clientEvents' => [
                    'fileuploaddone' => 'function(e, data) {
                                other_ids.push(data._response.result.files[0].media_id);
                            }'
                ],
            ]); ?>

            <hr>
            <?php
            if (!$model->isNewRecord) {
                ?>
                <table>

                    <?php
                    foreach ($media as $media_file) {

                        if($media_file->type != \app\models\Media::$OTHER){
                            continue;
                        }
                        echo '<tr style="border-bottom: 1px solid #c2c2c2;"><td><a target="_blank" href="'. Yii::$app->homeUrl . 'media/other/' . $media_file->filename . '">' . explode('_', $media_file->filename)[1] . '</a></td></tr>';
                    }
                    ?>
                </table>
                <?php
            }
            ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('ذخیره', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
    var image_ids = [];
    var audio_ids = [];
    var other_ids = [];

    $('#meeting-form').on('submit', function () {

        $('#hiddenImageFiles').val(image_ids);
        $('#hiddenAudioFiles').val(audio_ids);
        $('#hiddenOtherFiles').val(other_ids);
    });

    $('.media-delete').click(function () {
       media_filename = $(this).attr('data-filename');
       media_id = $(this).attr('data-media-id');
       media_type = $(this).attr('data-type');

       console.log(media_filename + ", " + media_id);

        $.get("<?=Yii::$app->homeUrl?>meeting/media-delete?name="+media_filename+"&media_id="+media_id+"&type="+media_type,
            function(data, status){
                location.reload();
            }
        );
    });
</script>
