<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style type="text/css">
        table, tr, td {
            border-collapse: collapse;
            text-align: right;
            width: 100%;
        }

        td {
            /*border: #333333 solid 1px;*/
            padding: 5px;
            vertical-align: top;
        }

        td#title {
            height: 40px;
            background-color: #c7c7c7;
            vertical-align: center;
            width: 60%;
            border-top-right-radius: 5px;
        }

        td#date {
            height: 40px;
            background-color: #c7c7c7;
            vertical-align: center;
            text-align: left;
            width: 40%;
            border-top-left-radius: 5px;
        }

        td#body {
            height: 200px;
            min-height: 100px;
            background-color: #eeeeee;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
        }
    </style>
</head>
<body>
    <?php $this->beginBody() ?>
    <?= $content ?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
