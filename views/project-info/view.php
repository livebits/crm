<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectInfo */

$project_name = (\app\models\Project::find()
    ->select('title')
    ->where('id='. $model->project_id)
    ->one())
    ->title;

$this->title = 'مشاهده اطلاعات پروژه: ' . $project_name;
$this->params['breadcrumbs'][] = ['label' => 'Project Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="project-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div>
        <?= Html::a('لیست اطلاعات پروژه ها', ['index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('ویرایش', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('حذف', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'مورد انتخاب شده حذف شود؟',
                'method' => 'post',
            ],
        ]) ?>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                "attribute" => 'project_id',
                "value" => function ($model) use($project_name) {
                    return $project_name;
                }
            ],
            'publish_version',
            'package_name',
//            'sign_file',
//            'keystore',
            'api_key',
            'key_alias_password',
            [
                "attribute" => 'created_at',
                "value" => function ($model) {
                    return \app\components\Jdf::jdate("Y/m/d", $model->created_at);
                }
            ],
            [
                "attribute" => 'updated_at',
                "value" => function ($model) {
                    if($model->updated_at) {
                        return \app\components\Jdf::jdate("Y/m/d", $model->updated_at);
                    } else {
                        return '';
                    }
                }
            ],
        ],
    ]) ?>

    <div class="panel panel-info">
        <div class="panel-heading">فایل sign</div>
        <div class="panel-body">
            <table>

                <?php

                if($model->sign_file) {
                    echo '<tr style="border-bottom: 1px solid #c2c2c2;"><td><a target="_blank" href="' . Yii::$app->homeUrl . 'media/project/attachments/' . $model->sign_file . '">' . explode('_', $model->sign_file)[2] . '</a></td></tr>';
                }

                ?>
            </table>
        </div>
    </div>

    <div class="panel panel-info">
        <div class="panel-heading">رمز Keystore</div>
        <div class="panel-body">
            <table>

                <?php

                if($model->keystore) {
                    echo '<tr style="border-bottom: 1px solid #c2c2c2;"><td><a target="_blank" href="' . Yii::$app->homeUrl . 'media/project/attachments/' . $model->keystore . '">' . explode('_', $model->keystore)[2] . '</a></td></tr>';
                }

                ?>
            </table>
        </div>
    </div>

</div>