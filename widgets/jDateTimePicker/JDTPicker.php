<?php
namespace app\widgets\jDateTimePicker;

use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\View;
use yii\widgets\InputWidget;

class JDTPicker extends InputWidget
{
    const PLUGIN_NAME = 'MdPersianDateTimePicker';
    public $clientOptions;
    private $_clientOptions = [
        'Placement' => 'top', // default is 'bottom'
        'Trigger' => 'click', // default is 'focus',
        'EnableTimePicker' => false, // default is true,
        'TargetSelector' => '', // default is empty,
        'GroupId' => '', // default is empty,
        'ToDate' => false, // default is false,
        'FromDate' => false, // default is false,
    ];
    public $type = 'text';
    private $_options = [
        'class' => 'form-control',
        'placeholder' => 'برای انتخاب تاریخ کلیک نمایید',
        'style' => 'direction:ltr;text-align:right;position:relative;'
    ];
    private $_hashVar;

    public function init()
    {
        if(!empty($this->_options)){
            $this->options = ArrayHelper::merge($this->options, $this->_options);
        }
        parent::init();
    }

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
        $this->clientOptions = ArrayHelper::merge($this->_clientOptions, $this->clientOptions);
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
        JDTPickerAsset::register($view);
        $view->registerJs($js);
    }
}