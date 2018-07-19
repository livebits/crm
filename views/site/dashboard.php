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
        <a href="<?=Yii::$app->homeUrl?>customer/contacts">
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-address-book"></i></div>
                <div class="count"><?=$contacts_count?></div>
                <h3>تعداد مخاطبین</h3>
            </div>
        </div>
        </a>

        <a href="<?=Yii::$app->homeUrl?>customer/index">
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-user"></i></div>
                <div class="count"><?=$clues_count?></div>
                <h3>تعداد سرنخ</h3>
            </div>
        </div>
        </a>

        <a href="<?=Yii::$app->homeUrl?>customer/off-customers">
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-user-times"></i></div>
                <div class="count"><?=$off_customer?></div>
                <h3>تعداد سرنخ غیرفعال</h3>
            </div>
        </div>
        </a>

        <a href="<?=Yii::$app->homeUrl?>customer/customers">
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-user-circle"></i></div>
                <div class="count"><?=$customers_count?></div>
                <h3>تعداد مشتری</h3>
            </div>
        </div>
        </a>

        <a href="<?=Yii::$app->homeUrl?>deal/all">
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-money"></i></div>
                    <div class="count"><?=$deals_count?></div>
                    <h3>تعداد معاملات</h3>
                </div>
            </div>
        </a>
    </div>
    <div class="row top_tiles">
        <a href="<?=Yii::$app->homeUrl?>meeting/late-customers">
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-calendar"></i></div>
                    <div class="count"><?=$lateCustomerMeetingsCount?></div>
                    <h3>جلسات عقب افتاده مشتریان</h3>
                </div>
            </div>
        </a>

        <a href="<?=Yii::$app->homeUrl?>meeting/late-deals">
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-calendar"></i></div>
                    <div class="count"><?=$lateDealMeetingsCount?></div>
                    <h3>جلسات عقب افتاده معاملات</h3>
                </div>
            </div>
        </a>

    </div>

</div>