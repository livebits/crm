<?php

namespace app\modules\api;

use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;

/**
 * api module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\api\controllers';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
//                HttpBasicAuth::className(),
                HttpBearerAuth::className(),
//                QueryParamAuth::className(),
            ],
        ];
        return $behaviors;
    }

    /**
     *
     */
    public function init()
    {
        parent::init();
        Yii::$app->user->enableSession = false;
        Yii::$app->user->loginUrl = null;

        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/api/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en_US',
            'basePath' => '@app/modules/api/messages',
            'fileMap' => [
                'modules/api/indexView' => 'indexView.php',
            ],
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/api/' . $category, $message, $params, $language);
    }

}
