<?php
namespace app\widgets\jdatepicker;

use yii\web\AssetBundle;

class JDatePickerAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap-jalali-datepicker';
    public $css = [
        'bootstrap-datepicker.min.css'
    ];
    public $js = [
        'bootstrap-datepicker.min.js',
        'bootstrap-datepicker.fa.min.js'
    ];
}