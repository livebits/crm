<?php


?>

<!--<a href="--><?//= Yii::$app->homeUrl ?><!--ticket/view?id=--><?//= $model->id ?><!--">-->

    <div class="panel <?= (!$model->reply_to || $model->status == \app\models\Ticket::EXPERT_REPLIED) ? 'panel-primary' : 'panel-info'?>">

        <div class="panel-heading">
            <span> کد:<?=$model->id?> | </span>
            <span> تاریخ: <?= \app\components\Jdf::jdate('Y/m/d H:i:s', $model->created_at) ?></span>
        </div>
        <div class="panel-body">
            <div class="ticket-desc">
                <?php
                echo $model->body;
                ?>
            </div>
        </div>
    </div>
    <div style="clear: both"></div>
<!--</a>-->