<?php

$this->title = 'داشبورد CRM';
?>
<style>

    .tile-stats h3 {
        margin-top: 10px;
    }

</style>

<div class="">
    <div class="row top_tiles">

        <a href="<?=Yii::$app->homeUrl?>ticket/index">
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-ticket"></i></div>
                    <div class="count"><?=$all_tickets?></div>
                    <h3>کل تیکت ها</h3>
                </div>
            </div>
        </a>

        <a href="<?=Yii::$app->homeUrl?>ticket/index">
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-ticket"></i></div>
                    <div class="count"><?=$done_tickets?></div>
                    <h3>انجام شده</h3>
                </div>
            </div>
        </a>

        <a href="<?=Yii::$app->homeUrl?>ticket/index">
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-ticket"></i></div>
                    <div class="count"><?=$in_progress_tickets?></div>
                    <h3>در حال انجام</h3>
                </div>
            </div>
        </a>

    </div>
    <div class="row top_tiles">

        <a href="<?=Yii::$app->homeUrl?>ticket/index">
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-ticket"></i></div>
                    <div class="count"><?=$waiting_tickets?></div>
                    <h3>منتظر پاسخ شما</h3>
                </div>
            </div>
        </a>

        <a href="<?=Yii::$app->homeUrl?>receipt/index">
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-money"></i></div>
                    <div class="count"><?=$dept_amount?></div>
                    <h3>بدهی</h3>
                </div>
            </div>
        </a>

        <a href="<?=Yii::$app->homeUrl?>deal/user-deals">
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-calendar"></i></div>
                    <div class="count"><?=$current_deals?></div>
                    <h3>قراردادهای جاری</h3>
                </div>
            </div>
        </a>

    </div>

</div>