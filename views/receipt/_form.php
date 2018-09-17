<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Receipt */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="receipt-form">

    <?php $form = ActiveForm::begin(
        [
            'id' => 'receipt-form',
            'options' => [
                'class' => 'form-horizontal form-label-left',
                'enctype' => 'multipart/form-data'
            ]
        ]
    ); ?>

    <input type="hidden" id="mediaFiles" name="mediaFiles" value="">

    <?= $form->field($model, 'bank_id')->dropDownList(
            \app\models\Receipt::banks()
    ) ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receipt_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="panel panel-info img-responsive">
        <div class="panel-heading">
            ضمیمه
        </div>
        <div class="panel-body">
            <?= \dosamigos\fileupload\FileUploadUI::widget([
                'model' => $mediaFile,
                'attribute' => 'otherFile',
                'url' => ['receipt/upload-attachment'],
                'gallery' => false,
                'fieldOptions' => [
//                    'accept' => 'images/*'
                ],
                'clientOptions' => [
                    'maxFileSize' => 2000000
                ],
                'clientEvents' => [
                    'fileuploaddone' => 'function(e, data) {
                                media_ids.push(data._response.result.files[0].media_id);
                            }'
                ],
            ]); ?>

            <hr>
            <?php

            if (!$model->isNewRecord) {
                $media = \app\models\Media::find()
                    ->where('meeting_id=' . $model->id)
                    ->andWhere('type="RECEIPT"')
                    ->all();
                ?>

                <table>
                    <?php
                    foreach ($media as $media_file) {

                        if($media_file->type != \app\models\Media::$RECEIPT){
                            continue;
                        }
                        echo '<tr style="border-bottom: 1px solid #c2c2c2;"><td><a target="_blank" href="'. Yii::$app->homeUrl . 'media/receipts/' . $media_file->filename . '">' . explode('_', $media_file->filename)[1] . '</a></td></tr>';
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
    var media_ids = [];

    $('#receipt-form').on('submit', function () {

        $('#mediaFiles').val(media_ids);
    });

    $('.media-delete').click(function () {
        media_filename = $(this).attr('data-filename');
        media_id = $(this).attr('data-media-id');
        media_type = $(this).attr('data-type');

        $.get("<?=Yii::$app->homeUrl?>receipt/media-delete?name="+media_filename+"&media_id="+media_id+"&type="+media_type,
            function(data, status){
                location.reload();
            }
        );
    });
</script>
