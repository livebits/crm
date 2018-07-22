<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DealLevel */

$this->title = 'مشاهده';
$this->params['breadcrumbs'][] = ['label' => 'Deal Levels', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deal-level-view">

    <div class="page-title">
        <div class="title_left">

        </div>
        <div class="title_right" style="width: 100%;text-align: left;">

            <?= Html::a('برگشت', ['index'], ['class' => 'btn btn-success']) ?>
            <?= Html::a('ویرایش', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('حذف', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'آیا از حذف این مورد اطمینان دارید؟',
                    'method' => 'post',
                ],
            ]) ?>
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

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'level_number',
                            'level_name',
                            [
                                'attribute' => 'created_at',
                                'value' => function($model) {
                                    return \app\components\Jdf::jdate('Y/m/d',$model->created_at);
                                }
                            ],
                        ],
                    ]) ?>

                </div>
            </div>
        </div>
    </div>

</div>
