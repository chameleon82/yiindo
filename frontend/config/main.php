<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'urlManager' => [
            'class'=>'frontend\components\LangUrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/index',
                'images/<mdl:[\w-]+>/<pid:\d+>/<filename:.+>' => 'images/default',
                '<_c:\w+>/<id:\d+>'=>'<_c>/view',
                '<_c:\w+>s' => '<_c>/index',
                '<module:(catalog)>/<_c:category>/<slug:[\w- ]+>' => '<module>/<_c>/index',
              //  '<module:(catalog)>/<_c:\w+>/<_a:\w+>/<id:\d+>' => '<module>/<_c>/view',
                'POST <controller:\w+>s' => '<controller>/create',
                'PUT <controller:\w+>/<id:\d+>'    => '<controller>/update',
                'DELETE <controller:\w+>/<id:\d+>' => '<controller>/delete',
            ]
        ],
        'request' => [
         //   'class' => 'frontend\components\LangRequest',
            'baseUrl' => ''
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'formatter' => [
            'dateFormat' => 'dd.MM.yyyy',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'RUR',
        ],
    ],
    'modules' => [
        'catalog' => [
            'class' => 'app\modules\catalog\Module',
        ],
        'gallery' => [
            'class' => 'app\modules\gallery\Module',
        ],
    ],
    'params' => $params,
];
