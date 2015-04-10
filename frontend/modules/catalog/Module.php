<?php

namespace app\modules\catalog;

use Yii;

class Module extends \yii\base\Module
{
    public $layout = 'main';

    public $controllerNamespace = 'app\modules\catalog\controllers';

    public function init()
    {
        parent::init();
        $this->registerTranslations();

       // $this->view->params['breadcrumbs'][]= $this->t('catalog','Catalog');
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/catalog/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/catalog/messages',
            'fileMap' => [
                'modules/catalog/catalog' => 'catalog.php',
            ],
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/catalog/' . $category, $message, $params, $language);
    }
}
