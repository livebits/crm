<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script src="<?= Yii::$app->homeUrl ?>vendors/jquery/dist/jquery.min.js"></script>
</head>
<body>
<?php $this->beginBody() ?>

<!-- /header content -->
<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col hidden-print">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="" class="site_title"><i class="fa fa-paw"></i> <span>AKAF CRM</span></a>
                </div>

                <div class="clearfix"></div>

                <!-- menu profile quick info -->
                <div class="profile clearfix">
                    <div class="profile_pic">
                        <a href="<?=Yii::$app->homeUrl?>site/profile">
                            <?php
                            if(!Yii::$app->user->isGuest) {
                                $userProfile = \app\models\UserProfile::find()
                                    ->where('user_id=' . Yii::$app->user->id)
                                    ->one();

                                if ($userProfile && $userProfile->image != "") {
                                    ?>
                                    <img src="<?= Yii::$app->homeUrl ?>Uploads/<?= $userProfile->image ?>" alt="..."
                                         class="img-circle profile_img">
                                    <?php
                                } else {
                                    ?>
                                    <img src="<?= Yii::$app->homeUrl ?>images/no_image.png" alt="..."
                                         class="img-circle profile_img">
                                    <?php
                                }
                            }
                            ?>
                        </a>
                    </div>
                    <div class="profile_info">
                        <h2><?=Yii::$app->user->username?></h2>
                        <?php
                        if(!Yii::$app->user->isGuest && $userProfile && $userProfile->firstName != "") {
                            ?>
                            <span><?php echo $userProfile->firstName . ' ' . $userProfile->lastName; ?> </span>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <!-- /menu profile quick info -->

                <br/>

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <h3>عمومی</h3>
                        <ul class="nav side-menu">

                            <?php
                            if(!Yii::$app->user->isSuperadmin)
                            {
                                $menu = \app\controllers\SiteController::getUserMenu();

                            }
                            else{
                                $menu = \app\controllers\SiteController::getAdminMenu();
                            }
                            foreach ($menu as $item) {
                                $item_url = strpos($item['name'], '/') == 0 ? substr($item['name'], 1): $item['name'];
                                ?>
                                <li>
                                    <a href="<?= Yii::$app->homeUrl.$item_url ?>">
                                        <i class="fa <?=$item['data']?>"></i>
                                        <span class="nav-label color"><?= $item['description']?></span>
                                    </a>
                                </li>
                            <?php }?>

                        </ul>
                    </div>

                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small hidden">
                    <a data-toggle="tooltip" data-placement="top" title="تنظیمات">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="تمام صفحه">
                        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="قفل">
                        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="خروج">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
                <!-- /menu footer buttons -->
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav hidden-print">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                               aria-expanded="false">
                                <?php
                                if(!Yii::$app->user->isGuest && $userProfile && $userProfile->image != "") {
                                    ?>
                                    <img src="<?=Yii::$app->homeUrl?>Uploads/<?=$userProfile->image?>">
                                    <?php
                                } else {
                                    ?>
                                    <img src="<?= Yii::$app->homeUrl ?>images/no_image.png">
                                    <?php
                                }
                                ?>
                                <?=Yii::$app->user->username?>
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">

                                <li><a href="<?=Yii::$app->homeUrl?>site/logout"><i class="fa fa-sign-out pull-right"></i> خروج</a></li>
                            </ul>
                        </li>

                        <li role="presentation" class="dropdown hidden">
                            <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown"
                               aria-expanded="false">
                                <i class="fa fa-envelope-o"></i>
                                <span class="badge bg-green">6</span>
                            </a>
                            <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                                <li>
                                    <a>
                                        <span class="image"><img src="<?=Yii::$app->homeUrl?>images/img.jpg"
                                                                 alt="Profile Image"/></span>
                                        <span>
                          <span>مرتضی کریمی</span>
                          <span class="time">3 دقیقه پیش</span>
                        </span>
                                        <span class="message">
                          فیلمای فستیوال فیلمایی که اجرا شده یا راجع به لحظات مرده ایه که فیلمسازا میسازن. آنها جایی بودند که....
                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <span class="image"><img src="<?=Yii::$app->homeUrl?>images/img.jpg"
                                                                 alt="Profile Image"/></span>
                                        <span>
                          <span>مرتضی کریمی</span>
                          <span class="time">3 دقیقه پیش</span>
                        </span>
                                        <span class="message">
                          فیلمای فستیوال فیلمایی که اجرا شده یا راجع به لحظات مرده ایه که فیلمسازا میسازن. آنها جایی بودند که....
                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <span class="image"><img src="<?=Yii::$app->homeUrl?>images/img.jpg"
                                                                 alt="Profile Image"/></span>
                                        <span>
                          <span>مرتضی کریمی</span>
                          <span class="time">3 دقیقه پیش</span>
                        </span>
                                        <span class="message">
                          فیلمای فستیوال فیلمایی که اجرا شده یا راجع به لحظات مرده ایه که فیلمسازا میسازن. آنها جایی بودند که....
                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <span class="image"><img src="<?=Yii::$app->homeUrl?>images/img.jpg"
                                                                 alt="Profile Image"/></span>
                                        <span>
                          <span>مرتضی کریمی</span>
                          <span class="time">3 دقیقه پیش</span>
                        </span>
                                        <span class="message">
                          فیلمای فستیوال فیلمایی که اجرا شده یا راجع به لحظات مرده ایه که فیلمسازا میسازن. آنها جایی بودند که....
                        </span>
                                    </a>
                                </li>
                                <li>
                                    <div class="text-center">
                                        <a>
                                            <strong>مشاهده تمام اعلان ها</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- /top navigation -->
        <!-- /header content -->

        <!-- page content -->
        <div class="right_col" role="main">
            <?= $content ?>
        </div>
        <!-- /page content -->


        <!-- footer content -->
        <footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; AKAF <?= date('Y') ?></p>

                <p class="pull-right">تمام حقوق برای آکاف محفوظ می باشد.</p>
                <p class="pull-right" style="margin-right: 10px;color: #0a0a0a;">  نسخه <?= Yii::$app->params['app_version']?></p>

            </div>
        </footer>
        <!-- /footer content -->
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
