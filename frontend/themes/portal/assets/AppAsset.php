<?php

namespace frontend\themes\portal\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/themes/portal/assets/source/';
   // public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/reset.css',
        'css/portal.css',
  //      'css/superfish.css',
    ];
    public $js = [
        'js/hoverIntent.js',
        'js/superfish.js',
    ];
    public $depends = [
        //'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
