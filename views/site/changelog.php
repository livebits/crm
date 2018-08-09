<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = 'تغییرات';
?>

<style>
    .change {
        margin-bottom: 20px;
        border-bottom: 1px dotted rgba(114, 114, 114, 0.86);
    }

    .version_date {
        color: #0a0a0a;
        margin-right: 10px;
    }

    .change-list {
        margin-top: 10px;
    }
</style>

<div class="site-error">

    <h3 class="well" style="margin-top: 60px;"><?= Html::encode($this->title) ?></h3>

    <div class="well">

        <div class="change">
            <span class="version_number pull-right">V 2.0.1</span>
            <span class="version_date pull-right">97/5/18</span>
            <div style="clear: both"></div>
            <ul class="change-list">
                <li>نمایش سرنخ های فعال در لیست جلسات عقب افتاده مشتریان</li>
            </ul>
        </div>

        <div class="change">
            <span class="version_number pull-right">V 2.0.0</span>
            <span class="version_date pull-right">97/5/10</span>
            <div style="clear: both"></div>
            <ul class="change-list">
                <li>افزودن وب سرویس های مشتریان و معاملات و مدیریت جلسات</li>
            </ul>
        </div>

        <div class="change">
            <span class="version_number pull-right">V 1.4.6</span>
            <span class="version_date pull-right">97/5/7</span>
            <div style="clear: both"></div>
            <ul class="change-list">
                <li>رفع باگ مشاهده جزییات جلسه</li>
            </ul>
        </div>

        <div class="change">
            <span class="version_number pull-right">V 1.4.5</span>
            <span class="version_date pull-right">97/5/5</span>
            <div style="clear: both"></div>
            <ul class="change-list">
                <li>رفع باگ تاریخ در لیست مشتریان و معاملات</li>
                <li>رفع باگ لیست جلسات معاملات</li>
            </ul>
        </div>

        <div class="change">
            <span class="version_number pull-right">V 1.4</span>
            <span class="version_date pull-right">97/5/2</span>
            <div style="clear: both"></div>
            <ul class="change-list">
                <li>مدیریت و شخصی سازی سطح های دسترسی مختلف برای ادمین / مدیر / کارمند</li>
            </ul>
        </div>

        <div class="change">
            <span class="version_number pull-right">V 1.3</span>
            <span class="version_date pull-right">97/5/1</span>
            <div style="clear: both"></div>
            <ul class="change-list">
                <li>مدیریت وظایف برای مشتریان</li>
                <li>مدیریت وظایف برای معاملات</li>
            </ul>
        </div>

        <div class="change">
            <span class="version_number pull-right">V 1.2.1</span>
            <span class="version_date pull-right">97/4/31</span>
            <div style="clear: both"></div>
            <ul class="change-list">
                <li>رفع باگ در مراحل قرارداد</li>
            </ul>
        </div>

        <div class="change">
            <span class="version_number pull-right">V 1.2</span>
            <span class="version_date pull-right">97/4/30</span>
            <div style="clear: both"></div>
            <ul class="change-list">
                <li>منو تغییرات (changelog)</li>
                <li>ثبت و مدیریت مراحل قرارداد</li>
                <li>جستجو در لیست معاملات</li>
            </ul>
        </div>

        <div class="change">
            <span class="version_number pull-right">V 1.1</span>
            <span class="version_date pull-right">97/4/28</span>
            <div style="clear: both"></div>
            <ul class="change-list">
                <li>منو سرنخ های خاموش</li>
                <li>منو جلسات عقب افتاده مشتریان</li>
                <li>منو جلسات عقب افتاده معاملات</li>
                <li>فیلتر و جستجو در سرنخ ها و مشتریان</li>
                <li>منو مخاطبین</li>
            </ul>
        </div>

    </div>

</div>
