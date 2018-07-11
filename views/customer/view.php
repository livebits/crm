<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Customer */

if($model->status == 0){
    $page_title = "مشاهده سرنخ";
} else if($model->status == 1){
    $page_title = "مشاهده مشتری";
} else if($model->status == 2){
    $page_title = "مشاهده معامله";
} else if($model->status == 3){
    $page_title = "مشاهده مخاطب";
}

$this->title = $page_title . ': ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('ویرایش', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('حذف', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'آیا از انتقال این مورد به مخاطبین اطمینان دارید؟',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'user_id',
            'firstName',
            'lastName',
            'companyName',
            'position',
            'mobile',
            'phone',
            [
                'attribute' => 'source',
                'value' => function($model) {
                    $source = \app\models\Source::find()
                        ->select('name')
                        ->where('id='.$model->source)
                        ->one();
                    return $source->name;
                }
            ],
            'description:ntext',
            [
                    'attribute' => 'created_at',
                    'value' => function($model) {
                            return \app\components\Jdf::jdate('Y/m/d',$model->created_at);
                    }
            ],
        ],
    ]) ?>

</div>
