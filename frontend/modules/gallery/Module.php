<?php

namespace app\modules\gallery;

use Yii;

class Module extends \yii\base\Module
{
    public $layout = 'main';

    public $controllerNamespace = 'app\modules\gallery\controllers';

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/gallery/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/gallery/messages',
            'fileMap' => [
                'modules/gallery/gallery' => 'gallery.php',
            ],
        ];

        Yii::$app->i18n->translations['gallery'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/gallery/messages',
            'fileMap' => [
                'gallery' => 'gallery.php',
            ],
        ];

    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/gallery/' . $category, $message, $params, $language);
    }
}
