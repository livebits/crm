<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Meeting */

$this->title = 'ویرایش جلسه: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Meetings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="meeting-update">

    <div class="page-title">
        <div class="title_left">

        </div>
        <div class="title_right" style="width: 100%;text-align: left;">

            <a href="<?=Yii::$app->homeUrl?>meeting/deal-index?deal_id=<?=$_GET['deal_id']?>" class="btn btn-success">لیست جلسات</a>
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

                    <?= $this->render('_form', [
                        'model' => $model,
                        'deal' => $deal,
                        'user' => $user,
                        'imageMediaFile' => $imageMediaFile,
                        'soundMediaFile' => $soundMediaFile,
                        'otherMediaFile' => $otherMediaFile,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>

</div>
