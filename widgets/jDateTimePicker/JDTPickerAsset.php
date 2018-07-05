<?php
namespace app\widgets\jDateTimePicker;

use yii\web\AssetBundle;

class JDTPickerAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap-persian-datetime-picker';
    public $css = [
        'jquery.Bootstrap-PersianDateTimePicker.css'
    ];
    public $js = [
        'calendar.js',
        'jquery.Bootstrap-PersianDateTimePicker.js'
    ];
}