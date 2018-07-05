<?php
namespace app\widgets\jdatepicker;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use yii\widgets\InputWidget;

class JDatePicker extends InputWidget
{
    const PLUGIN_NAME = 'datepicker';
    public $clientOptions = [
        'isRTL' => true,
        'dateFormat' => "yy/m/d",
        'changeMonth' => true,
        'changeYear' => true,
        'showMonthAfterYear' => false,
        'minYear' => 1320,
        'yearRange' => 'c-90:c+10',
        'hideIfNoPrevNext' => true
    ];
    public $type = 'text';
    public $options = [
        'class' => 'form-control',
        'placeholder' => 'برای انتخاب تاریخ کلیک نمایید',
        'style' => 'direction:ltr;text-align:right;'
    ];
    private $_hashVar;

    public function run()
    {
        $this->registerClientScript();
        if ($this->hasModel()) {
            echo Html::activeInput($this->type, $this->model, $this->attribute, $this->options);
        } else {
            echo Html::input($this->type, $this->name, $this->value, $this->options);
        }
    }

    /**
     * @param $view View
     */
    protected function hashPluginOptions($view)
    {
        $encOptions = empty($this->clientOptions) ? '{}' : Json::htmlEncode($this->clientOptions);
        $this->_hashVar = self::PLUGIN_NAME . '_' . hash('crc32', $encOptions);
        $this->options['data-plugin-' . self::PLUGIN_NAME] = $this->_hashVar;
        $view->registerJs("var {$this->_hashVar} = {$encOptions};\n", View::POS_HEAD);
    }

    public function registerClientScript()
    {
        $js = '';
        $view = $this->getView();
        //$this->initClientOptions();
        $this->hashPluginOptions($view);
        $id = $this->options['id'];
        $js .= '$("#' . $id . '").' . self::PLUGIN_NAME . "(" . $this->_hashVar . ");\n";
        JDatePickerAsset::register($view);
        $view->registerJs($js);
    }
}