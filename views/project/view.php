<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Project */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('لیست پروژه ها', ['index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('ویرایش', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('حذف', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'پروژه انتخاب شده حذف شود؟',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                "attribute" => 'programming_lang',
                "value" => function($model) {
                    return \app\models\Project::languages(intval($model->programming_lang));
                }
            ],
            'description:ntext',
            [
                "attribute" => 'created_at',
                "value" => function($model) {
                    return \app\components\Jdf::jdate("Y/m/d", $model->created_at);
                }
            ],
        ],
    ]) ?>

</div>
