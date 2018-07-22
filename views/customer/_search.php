<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\CustomerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-search">

    <div class="page-title">
        <div class="title_left">

        </div>
        <div class="title_right" style="width: 100%;text-align: left;">

        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>جستجو</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br/>

                    <?php $form = ActiveForm::begin([
//                        'action' => ['index'],
                        'method' => 'get',
                    ]); ?>

                    <div class="row">
                        <div class="col-md-4" style="margin-bottom: 30px;">

                            <?= $form->field($model, 'id')->widget(Select2::className(), [
                                'initValueText' => !empty($model['id']) ? \app\models\Customer::getPassengerName($model['id']) : null,
                                'options' => [
                                    'dir' => 'rtl',
                                    'placeholder' => 'نام مشتری را وارد کنید'
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 3,
                                    'ajax' => [
                                        'url' => \yii\helpers\Url::to(['customer/search-customer-name']),
                                        'dataType' => 'json',
                                        'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                                    ]
                                ]
                            ])->label('نام مشتری') ?>
                        </div>

                        <div class="col-md-4">
                            <?php echo $form->field($model, 'Mobile')->label('موبایل') ?>
                        </div>

                        <div class="col-md-4">
                            <?= $form->field($model, 'Source')
                                ->label('منبع')
                                ->widget(Select2::className(),
                                    ['data' => $model->get_all_sources(),
                                        'options' => [
                                            'dir' => 'rtl',
                                            'placeholder' => 'منبع معرف را انتخاب کنید'
                                        ]
                                    ])
                            ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <?= Html::submitButton('جستجو', ['class' => 'btn btn-primary']) ?>
                            <?= Html::resetButton('پاک کردن فیلترها', ['class' => 'btn btn-danger']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>
