<?php
/**
 * @var $model \app\models\UserDriver
 */

use yii\widgets\DetailView;

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'body:ntext'
    ]
]);