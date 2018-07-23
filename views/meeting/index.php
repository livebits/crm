<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MeetingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

if($customer->status == 0){
    $page_title = "سرنخ";
} else if($customer->status == 1){
    $page_title = "مشتری";
} else if($customer->status == 2){
    $page_title = "معامله";
} else if($customer->status == 3){
    $page_title = "مخاطب";
}

if(isset($_GET['deal_id'])){
    $deal = \app\models\Deal::findOne($_GET['deal_id']);
    $page_title = "معامله";
    $this->title = 'پرونده ' . $page_title . ' : ' . $deal->subject;

} else {
    $this->title = 'پرونده ' . $page_title . ' : ' . $customer->firstName . ' ' . $customer->lastName;

}

$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .delete-task {
        color: red;
        border: 1px solid rgba(135,135,135,0.49);
        width: 20px;
        height: 20px;
        text-align: center;
        margin-left: -20px;
    }
    .delete-task:hover {
        background-color: #ff605b;
        color: #ffffff;
    }
</style>
<div class="meeting-index">

    <div class="page-title">
        <div class="title_left">

        </div>
        <div class="title_right" style="width: 100%;text-align: left;">

            <?php
            if(isset($_GET['deal_id'])){

                $deal = \app\models\Deal::findOne($_GET['deal_id']);
                ?>
                <a href="create-deal-meeting?deal_id=<?= $_GET['deal_id'] ?>" class="btn btn-success">ثبت جلسه</a>
                <?php

                $arr = explode('/', Yii::$app->request->referrer);
                if($arr[count($arr) - 1] == "all") {
                    ?>

                    <a href="<?= Yii::$app->homeUrl ?>deal/all"
                       class="btn btn-success">لیست معاملات </a>

                    <?php
                } else {
                    ?>
                    <a href="<?= Yii::$app->homeUrl ?>deal/index?customer_id=<?= $deal->customer_id ?>"
                       class="btn btn-success">لیست معاملات مشتری</a>

                    <?php
                }
            } else {
                if ($customer->status != 3) {
                    ?>
                    <a href="create?customer_id=<?= $_GET['customer_id'] ?>" class="btn btn-success">ثبت جلسه</a>

                    <?php
                }
            }
            ?>
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

                    <div class="row customer-doc">
                        <div class="col-md-4 doc-right">

                            <div class="customer-image">
                                <?php
                                    if(isset($customer->image) && $customer->image != "") {
                                        ?>
                                        <img src="<?= Yii::$app->homeUrl ?>Uploads/<?= $customer->image ?>"
                                             class="img-responsive img-circle">
                                        <?php
                                    } else {
                                        ?>
                                        <img src="<?= Yii::$app->homeUrl ?>images/no_image.png"
                                             class="img-responsive img-circle">
                                        <?php
                                    }
                                ?>
                            </div>

                            <div class="customer-info">
                                <p><?php echo $customer->firstName . ' ' . $customer->lastName ?></p>
                                <p>کد: <?php echo $customer->id ?></p>
                                <p>سمت: <?php echo $customer->position ?></p>
                                <p>شرکت: <?php echo $customer->companyName ?></p>
                                <p>موبایل: <?php echo $customer->mobile ?></p>
                            </div>

                            <button class="btn btn-link more-btn" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                بیشتر...
                            </button>
                            <div class="collapse" style="clear: both;" id="collapseExample">

                                <div class="customer-info">
                                    <p>تلفن: <?php echo $customer->phone ?></p>
                                    <p>منبع: <?php echo $customer->source ?></p>
                                    <p>تاریخ ثبت: <?= \app\components\Jdf::jdate('Y/m/d', $customer->created_at) ?></p>
                                </div>
                            </div>

                            <div class="more-box-title">توضیحات</div>
                            <div class="more-box">
                                <?php echo $customer->description ?>
                            </div>

                            <div class="more-box-title">وظایف</div>
                            <div class="more-box">

                                <div class="panel panel-info">

                                    <div class="panel-heading">

                                        <?php $form = ActiveForm::begin([
                                            'action' => Yii::$app->homeUrl . 'task/add-task',
                                        ]); ?>

                                        <input type="hidden" name="customer_id" id="customer_id" value="<?=$customer->id?>">

                                        <?php
                                        if(isset($_GET['deal_id'])) {
                                            ?>
                                            <input type="hidden" name="deal_id" value="<?= $_GET['deal_id'] ?>">
                                            <?php
                                        }
                                        ?>

                                        <div class="form-group pull-right" style="width: 200px;">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="task_name" name="task_name" placeholder="وظیفه">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success pull-right">ثبت</button>
                                        <?php ActiveForm::end(); ?>

                                    </div>

                                    <?php $form = ActiveForm::begin([
                                        'action' => Yii::$app->homeUrl . 'task/save-changes',
                                    ]); ?>

                                    <input type="hidden" name="customer_id" value="<?=$_GET['customer_id']?>">
                                    <?php
                                    if(isset($_GET['deal_id'])) {
                                        ?>
                                        <input type="hidden" name="deal_id" value="<?= $_GET['deal_id'] ?>">
                                        <?php
                                    }
                                    ?>

                                    <div class="panel-body">

                                        <?php
                                        foreach ($tasks as $task){
                                            ?>

                                            <div class="" style="border: 1px solid rgba(135,135,135,0.49);padding: 5px 5px 5px 25px;margin-bottom: 10px;border-radius: 2px;">

                                                <label class="checkbox-inline" style="margin-right: 0px;margin-left: 10px;">
                                                    <input type="checkbox" value="<?=isset($task->is_done) ? 1 : 0;?>" <?php if($task->is_done) echo 'checked=""';?> id="task_<?=$task->id?>" name="task[<?=$task->id?>]"><?=$task->name?>
                                                </label>

                                                <a class="pull-left delete-task" href="<?=Yii::$app->homeUrl?>task/delete-task?id=<?=$task->id?>">x</a>
                                            </div>

                                            <?php
                                        }
                                        ?>

                                    </div>
                                    <div class="panel-footer">
                                        <button type="submit" class="btn btn-info btn-block pull-right">اعمال تغییرات</button>
                                        <?php ActiveForm::end(); ?>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="col-md-8 doc-left">

                            <?php
                            echo \yii\widgets\ListView::widget( [
                                'dataProvider' => $dataProvider,
                                'itemView' => 'customer_meeting_item',
                            ] );
                            ?>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
