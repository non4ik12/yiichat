<?php

$params = require __DIR__ . '/params.php';

$config = [
    'id'         => 'basic',
    'basePath'   => dirname(__DIR__),
    'bootstrap'  => ['log'],
    'components' => [
        'request'      => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'JKSDFHfhdskjfhksdjhfjds',
        ],
        'cache'        => [
            'class' => 'yii\caching\FileCache',
        ],
        // 'user' => [
        //     'identityClass' => 'app\models\User',
        //     'enableAutoLogin' => true,
        // ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer'       => [
            'class'            => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db'           => require __DIR__ . '/db.php',
        'urlManager'   => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [
                'register'   => 'user/registration/register',
                'admin/chat' => 'olchat',
                'admin/chat/<action>' => 'olchat/default/<action>',
            ],
        ],
    ],
    'modules'    => [
        'user'   => [
            'class'  => 'dektrium\user\Module',
            'admins' => ['nonko', 'admin', 'only_you'],
        ],
        'admin'  => [
            'class' => 'app\modules\admin\Module',
        ],
        'olchat' => [
            'class' => 'app\modules\olchat\Module',
        ],
    ],
    'params'     => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][]      = 'debug';
    $config['modules']['debug'] = [
        'class'      => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '188.166.71.119', '77.87.144.11', '188.239.36.231', '185.128.232.242', '88.81.226.126'],
    ];

    $config['bootstrap'][]    = 'gii';
    $config['modules']['gii'] = [
        'class'      => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '188.166.71.119', '188.239.36.231', '185.128.232.242', '88.81.226.126'],
    ];
}

return $config;
