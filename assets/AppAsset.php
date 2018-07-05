<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/fontiran.css',
        'vendors/bootstrap/dist/css/bootstrap.min.css',
        'vendors/bootstrap-rtl/dist/css/bootstrap-rtl.min.css',
        'vendors/font-awesome/css/font-awesome.min.css',
        'vendors/nprogress/nprogress.css',
        'vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css',
        'vendors/bootstrap-daterangepicker/daterangepicker.css',
        'vendors/iCheck/skins/flat/green.css',
        'css/custom.min.css',
        'css/site.css',
    ];
    public $js = [
//        'vendors/jquery/dist/jquery.min.js',
        'vendors/bootstrap/dist/js/bootstrap.min.js',
        'vendors/fastclick/lib/fastclick.js',
        'vendors/nprogress/nprogress.js',
        'vendors/bootstrap-progressbar/bootstrap-progressbar.min.js',
        'vendors/iCheck/icheck.min.js',
        'vendors/moment/min/moment.min.js',
        'vendors/bootstrap-daterangepicker/daterangepicker.js',
        'vendors/Chart.js/dist/Chart.min.js',
        'vendors/jquery-sparkline/dist/jquery.sparkline.min.js',
        'vendors/gauge.js/dist/gauge.min.js',
        'vendors/skycons/skycons.js',
        'vendors/gauge.js/dist/gauge.min.js',
        'vendors/Flot/jquery.flot.js',
        'vendors/Flot/jquery.flot.pie.js',
        'vendors/Flot/jquery.flot.time.js',
        'vendors/Flot/jquery.flot.stack.js',
        'vendors/Flot/jquery.flot.resize.js',
        'vendors/DateJS/build/production/date.min.js',
        'js/custom.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset'
    ];
}