<?php
return [
    'id' => 'YiiNDO CMS',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'ru-RU',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['user'],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yiindo',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'tablePrefix' => 'ndo_',
        ],
    ],
    'modules' => [

    ],
    'aliases' => [
        '@static' => dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'static',
        '@webstatic' => '/static/',
        ]
];
