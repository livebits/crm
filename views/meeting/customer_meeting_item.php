<?php

use yii\helpers\Html;

?>
<?php
if(!isset($_GET['deal_id'])) {
?>
<a href="<?= Yii::$app->homeUrl ?>meeting/view?id=<?= $model->id ?>&customer_id=<?= $_GET['customer_id'] ?>">
    <?php
    } else {
        ?>
    <a href="<?= Yii::$app->homeUrl ?>meeting/view-deal?id=<?= $model->id ?>&deal_id=<?= $_GET['deal_id'] ?>">
        <?php
    }
    ?>
    <div class="meeting-box">

        <div class="meet-info">
            <span><?= $model->username ?></span> /
            <span>تاریخ جلسه:<?= \app\components\Jdf::jdate('Y/m/d', $model->created_at) ?></span> /
            <span>تاریخ پیگیری:<?= \app\components\Jdf::jdate('Y/m/d', $model->next_date) ?></span>
        </div>
        <div class="meet-code">کد: <?= $model->id ?></div>
        <div class="meet-desc">
            <?php
            echo $model->content;
            ?>
        </div>
        <div class="meet-attachs">
            <span>صوت: <?= isset($model->audiosCount) ? $model->audiosCount : 0 ?></span>
            <span>عکس: <?= isset($model->imagesCount) ? $model->imagesCount : 0 ?></span>
            <span>ضمیمه: <?= isset($model->attachsCount) ? $model->attachsCount : 0 ?></span>
        </div>
        <div style="clear: both"></div>
    </div>
</a>