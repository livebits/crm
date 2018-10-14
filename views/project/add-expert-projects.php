<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\grid\GridView;

$this->title = 'افزودن کارشناس به پروژه';
/* @var $this yii\web\View */
/* @var $model app\models\Ticket */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="deal-create">

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
                    <h2><?= Html::encode($this->title) ?></h2>
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
                        'options' => [
                            'class' => 'form-vertical',
                        ]
                    ]); ?>

                    <div class="col-md-4">
                        <?= $form->field($model, 'project_id')
                            ->widget(Select2::className(), ['data' => $projects, 'options' => ['dir' => 'rtl', 'placeholder' => 'پروژه مورد نظر را انتخاب کنید']]) ?>
                    </div>

                    <div class="col-md-4">
                        <?= $form->field($model, 'expert_id')
                            ->widget(Select2::className(), ['data' => $users, 'options' => ['dir' => 'rtl', 'placeholder' => 'کارشناس مرتبط را انتخاب کنید']]) ?>
                    </div>

                    <div class="col-md-2" style="padding-top: 23px;">
                        <?= Html::submitButton('ذخیره', ['class' => 'btn btn-success']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                    <hr>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
//                            'id',
                            [
                                "attribute" => 'username',
                                'label' => 'نام کارشناس',
                            ],
                            [
                                "attribute" => 'title',
                                'label' => 'نام پروژه',
                            ],
                            [
                                "attribute" => 'created_at',
                                'label' => 'تاریخ ثبت',
                                "value" => function($model) {
                                    return \app\components\Jdf::jdate("Y/m/d", $model['created_at']);
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{delete}',
                                'buttons' => [
                                    'delete' => function ($url, $model, $key) {

                                        $url = Yii::$app->urlManager->createUrl([
                                            'project/delete-expert-project',
                                            'id' => $model['id'],
                                        ]);
                                        return "<a href=". $url ." title='حذف'><i class='fa fa-trash'></i></a>";
                                    }
                                ]
                            ],
                        ],
                    ]); ?>

                </div>
            </div>
        </div>
    </div>

</div>