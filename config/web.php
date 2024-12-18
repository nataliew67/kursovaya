<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name'=>'sweet-shop',
 'language'=>'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'grechushnikova',
            'parsers' => [
 'application/json' => 'yii\web\JsonParser',
            ],        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
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
        'db' => $db,
        
        'urlManager' => [
'enablePrettyUrl' => true,
'enableStrictParsing' => true,
'showScriptName' => false, 

            'rules' => [
            'POST register'=>'users/create',
            'POST login'=>'users/login',
            'GET users' => 'users/view',
            'PATCH change' =>'users/change',
            'GET painting' => 'paintings/view',
            'PATCH painting/edit' => 'paintings/edit',
            'GET painting/search' => 'paintings/search',
            'POST painting/addnew' => 'paintings/create',
            'GET cart/items' =>'cart/items',
            'PATCH cart/items' => 'cart/edit',
            'POST cart/new' => 'cart/new',
            'DELETE cart/del' => 'cart/delete',
            'POST order/new' => 'orders/create',
            'GET orders/' => 'orders/view'
            ],
        ],
        
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1','*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1','*'],
    ];
}

return $config;
