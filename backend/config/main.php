<?php
use \yii\web\Request;
//Yii::$app->log->targets['debug'] = null;
$baseUrl = str_replace('/backend/web', '', (new Request)->getBaseUrl()).'/admin';
$frontEndBaseUrl = str_replace('/backend/web', '/frontend/web', (new Request)->getBaseUrl());
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        [
            'class' => 'app\components\LanguageSelector',
            'supportedLanguages' => ['en_US', 'ru_RU','zh-CN' => 'Chinese'],
        ],
  ],
  'language' => ['en-US', 'en', 'fr'],
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
   

    ],
    'defaultRoute' => 'admin',
    'on beforeRequest' => function ($event) {
        \Yii::$app->language = Yii::$app->session->get('language');
        },
    'components' => [

        'assetManager' => [
            'bundles' => [
 
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js'=>[]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => []
                ]
            ]
        ],

       'urlManagerfrontEnd' => [
        'class' => 'yii\web\urlManager',
        'enablePrettyUrl' => false,
        'showScriptName' => false,
        'baseUrl' => $frontEndBaseUrl,
    ],

 'frontendCache' => [
        'class' => 'yii\caching\FileCache',
        'cachePath' => Yii::getAlias('@frontend') . '/runtime/cache'
    ],
    
        'request' => [
            'csrfParam' => '_csrf-backend',
            'baseUrl' => $baseUrl,
        ],
      
             
        'user' => [
            'identityClass' => 'common\models\Admin',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_backendUser','path'=>'/backend/web','httpOnly' => true],
        ],
        'users' => [
    'class' => 'yii\web\DbSession',
    'writeCallback' => function ($session) {
        return [
           'user_id' => Yii::$app->user->userId,
           'lastLoginDate' => time(),
        ];
    },
],

        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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

        'Myclass' => [
            'class' => 'common\components\Myclass',
        ],
        
        'urlManager' => [
            'baseUrl' => $baseUrl,
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
           
            '/index' => 'admin/index',
            '/' => 'admin/login',
            'invoices/getinvoicedata'=>'invoices/getinvoicedata',
            '<controller:\w+>/<id:\d+>' => '<controller>/view',
            '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
            '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',       
            '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
            '<module:\w+><controller:\w+>/<action:update|delete>/<id:\d+>' => '<module>/<controller>/<action>',
            ],


        ],
        'i18nJs' => [
      'class' => 'w3lifer\yii2\I18nJs',
    ],
        
    ],
    'params' => $params,
];
