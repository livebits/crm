<?php

$this->title = 'داشبورد CRM';
?>

<div class="">
    <div class="row top_tiles">
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-caret-square-o-right"></i></div>
                <div class="count"><?=$clues_count?></div>
                <h3>تعداد سرنخ</h3>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-comments-o"></i></div>
                <div class="count"><?=$customers_count?></div>
                <h3>تعداد مشتری</h3>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-sort-amount-desc"></i></div>
                <div class="count"><?=$deals_count?></div>
                <h3>تعداد معاملات</h3>
            </div>
        </div>
    </div>
</div>