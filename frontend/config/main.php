<?php
use \yii\web\Request;
use yii\helpers\Url;

$baseUrl = str_replace('/frontend/web', '', (new Request)->getBaseUrl());
$backEndBaseUrl = str_replace('/frontend/web', '/backend/web', (new Request)->getBaseUrl());
$frontEndBaseUrl = str_replace('/backend/web', '/frontend/web', (new Request)->getBaseUrl());

$params = array_merge(
  require __DIR__ . '/../../common/config/params.php',
  require __DIR__ . '/../../common/config/params-local.php',
  require __DIR__ . '/params.php',
  require __DIR__ . '/params-local.php'
);

return [
  'id' => 'app-frontend',
  'basePath' => dirname(__DIR__),
  'bootstrap' => [
    [
      'class' => 'app\components\LanguageSelector',
      'supportedLanguages' => ['en_US', 'ru_RU', 'zh-CN' => 'Chinese'],
    ],
  ],
  'language' => ['en-US', 'en', 'fr', 'ar'],
  'bootstrap' => ['log'],
  'controllerNamespace' => 'frontend\controllers',
  'on beforeRequest' => function ($event) {
    \Yii::$app->language = Yii::$app->session->get('language');
  },
  'components' => [
 'thumbnailer' => [
        'class' => 'daxslab\thumbnailer\Thumbnailer',
    ],
    'urlManagerBackEnd' => [
      'class' => 'yii\web\urlManager',
      'enablePrettyUrl' => true,
      'showScriptName' => false,
      'baseUrl' => $backEndBaseUrl,
    ],


    'urlManagerfrontEnd' => [
      'class' => 'yii\web\urlManager',
      'enablePrettyUrl' => true,
      'showScriptName' => false,
      'baseUrl' => $frontEndBaseUrl,
    ],

    'assetManager' => [
        'bundles' => [
            'yii\bootstrap\BootstrapPluginAsset' => [
                'js'=>[]
            ],
            'yii\web\JqueryAsset' => [
                'js'=>[]
            ],
        ],
    ],
    'request' => [
      'csrfParam' => '_csrf-frontend',
      'baseUrl' => $baseUrl,
    ],
    'user' => [
      'identityClass' => 'common\models\Users',
      'enableAutoLogin' => true,

    ],

    'session' => [
         
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

        'authClientCollection' => [
          'class' => 'yii\authclient\Collection',
          'clients' => [
            'facebook' => [
              'class' => 'yii\authclient\clients\Facebook',
              'authUrl' => 'https://www.facebook.com/dialog/oauth?display=popup',
              'attributeNames' => ['name', 'email', 'first_name', 'last_name','picture'],
            ],
            'google' => [
              'class' => 'yii\authclient\clients\Google',
            ],
          ],
        ],

        'urlManager' => [
          'baseUrl' => $baseUrl,
          'class' => 'yii\web\UrlManager',
          'enablePrettyUrl' => true,
          'showScriptName' => false,

          'rules' => [
            '/' => 'site/index',  
            'sitemap' =>'site/sitemap',
            'map'=>'site/map',
            'sitemaintenance/'=>'site/sitemaintenance',
            'products/startfileupload'=>'products/startfileupload',
            'site/getdate'=>'site/getdate',
            'site/adverister' => 'site/adverister',
            'site/addadverister'=>'site/addadverister',
            'site/addbanner'=>'site/addbanner',
			'site/search'=>'site/search',
			'site/loginwithgoogle' => 'site/loginwithgoogle',
            'products/getrangefilter'=>'products/getrangefilter',
            'products/getlatlon' => 'products/getlatlon',
            'products/getchildlevel' => 'products/getchildlevel',
            'currentloc'=>'site/currentloc',
            'site/successcheck'=>'site/successcheck',
            'site/banneripnprocess'=>'site/banneripnprocess',
            'site/paymentprocess'=>'site/paymentprocess',
            'products/getfilter'=>'products/getfilter',
            'products/getsubfilter'=>'products/getsubfilter',
            'products/getsubcategory'=>'products/getsubcategory',
            'products/getupdatefilter'=>'products/getupdatefilter',
            'promotioncron' => 'site/promotioncron',
            'message/updatechat' => 'message/updatechat',
            '<action:(verify)>/<details>' => 'user/<action>',
            'site/ajaxsignup' => 'site/ajaxsignup',
            'site/getiplocation' => 'site/getiplocation',
            'site/userlogin' => 'site/userlogin',
            'site/loginwithgoogle' => 'site/loginwithgoogle',
            'site/paysession' => 'site/paysession',
            'user/historyview' => 'user/historyview/',
            'message' => 'message/index',
            'autosearch' => 'site/autosearch',
            'message/chataction' => 'message/chataction',
            'message/help' => 'message/help/',
            'products/productproperty' => 'products/productproperty',
            'site/auth' => 'site/auth',
            'site/login' => 'site/login',
            'site/locationplacename' => 'site/locationplacename',
            'site/signup' => 'site/signup',
            'site/forgotpassword' => 'site/forgotpassword',
            'site/logout' => 'site/logout',
            'site/captcha' => 'site/captcha',
            'site/checkstatus'=>'site/checkstatus',
            'site/getfiltervalues'=>'site/getfiltervalues',
            'user/mobileverificationstatus' => 'user/mobileverificationstatus/',
            'user/mobileverificationstatusfirebase' => 'user/mobileverificationstatusfirebase/',
            'site/language' => 'site/language',
            'site/socialsignup' => 'site/socialsignup',
            'site/ajax-request-password-reset/' => 'site/ajax-request-password-reset/',
            'user/sociallogin' => 'user/sociallogin',
            'user/editprofile' => 'user/editprofile',
            'user/getsocialaccess' => 'user/getsocialaccess',
            'user/changepassword' => 'user/changepassword',
            'user/notification' => 'user/notification',
            'user/exchanges' => 'user/exchanges',
            'user/getexchanges' => 'user/getexchanges',
            'user/promotions' => 'user/promotions',
            'user/imageupload' => 'user/imageupload',
            'user/upload' => 'user/upload',
            'user/liked' => 'user/liked',
            'user/twilio' => 'user/twilio',
            'user/verify_mail' => 'user/verify_mail',
            'user/follower' => 'user/follower',
            'user/following' => 'user/following',
            'user/phonevisible' => 'user/phonevisible',
            'user/advertise'=>'user/advertise',
            'user/adsview/<id:\w+>'=>'user/adsview',
            'products/create' => 'products/create',
            'products/view' => 'products/view',
            'products/insights' => 'products/insights',
            'products/success/' => 'checkout/success',
            'checkout/Canceled' => 'checkout/canceled',
            'checkout/mypayment/' => '/checkout/mypayment',
            'checkout/mystripepayment/' => '/checkout/mystripepayment',
            'checkout/canceled/' => '/checkout/canceled',
            'checkout/mypaymentcheckout/' => 'checkout/mypaymentcheckout',
            'user/uploadfile' => 'user/uploadfile',
            'revieworder/<details:\w+>' => '/checkout/revieworder',
            'checkout/addshipping' => '/checkout/addshipping',
            'products/undoreport' => 'products/undoreport',
            '/user/advertisepromotions' => '/user/advertisepromotions',
            'user/expiredpromotions' => 'user/expiredpromotions',
            'user/profile' => 'user/profile',
            'user/profiles' => 'user/profiles',
            'site/mail' => 'site/mail',
            'site/sociallogin/<type:\w+>' => 'site/sociallogin/',
            '/orders' => 'buynow/orders',
            'vieworders/<id:\w+>' => 'buynow/vieworders',
            'viewsales/<id:\w+>' => 'buynow/viewsales',
            'sales' => 'buynow/sales',
            'buynow/review/<id:\w+>' => 'buynow/review',
            'shippingaddress' => 'buynow/shippingaddress',
            'buynow/shippingaddress' => 'buynow/shippingaddress',
            'buynow/getshipping' => 'buynow/getshipping',
            'buynow/delete/<id:\w+>' => 'buynow/delete',
            'buynow/default' => 'buynow/default/<id:\d+>/<user:\d+>',
            'buynow/changestatus' => 'buynow/changestatus',
            'buynow/viewinvoice' => 'buynow/viewinvoice',
            'buynow/updatereview' => 'buynow/updatereview',
            'products/insights' => 'products/insights',
            'products/itemsdata'=>'products/itemsdata',
            'products/requestexchange' => 'products/requestexchange',
            'products/selectExchangeproduct' => 'products/selectExchangeproduct',
            'products/promotionpaymentprocess' => 'products/promotionpaymentprocess',
            'products/promotionstripepaymentprocess' => 'products/promotionstripepaymentprocess',
            'message/postmessage' => 'message/postmessage',
            'initiatechat' => 'products/initiatechat',
            'login' => 'site/login',
            'message/<id:\d+>' => 'message/index',
            'message/<id:\w+>' => 'message/index',
            'category' => 'site/search',
            'category/<category:\w+>' => 'site/search',
            'category/<category:\w+>/<subcategory:\w+>' => 'site/search',
            'category/<category:\w+>/<subcategory:\w+>/<sub_subcategory:\w+>' => 'site/search',

            'categorys' => 'site/searchnew',
            'categorys/<category:\w+>' => 'site/searchnew',
            'categorys/<category:\w+>/<subcategory:\w+>' => 'site/searchnew',  

            'products/view/<id:\w+>/<products:\w+>' => '/products/view',
            'products/like/<id:\w+>' => '/products/like',
            'products/savecomment' => '/products/savecomment',
            'products/deletecomment' => '/products/deletecomment',
            'products/delete/<id:\w+>' => '/products/delete',
            'products/update/<id:\w+>' => '/products/update',
            'products/dislike/<id:\w+>' => '/products/dislike',
            
            //'category/<category:\w+>/<subcategory:\w+>/<filters:\w+>/<filterid:\w+>' => 'site/search',     
            'buynow/tracking/<id:\w+>' => '/buynow/tracking',
            'buynow/edit_tracking_details' => 'buynow/edit_tracking_details',
            '/checkout/revieworder2/<details:\w+>' => '/checkout/revieworder2',
            'user/exchangeview/<id:\w+>' => '/user/exchangeview',
            'user/message/<id:\w+>' => '/user/message',
            'user/getSocialAccess' => 'user/getSocialAccess',
            '/api/Followersdetails' => '/api/followersdetails',
            '/api/Followingdetails' => '/api/followingdetails',
            '/api/Followuser' => '/api/followuser',
            '/api/Unfollowuser' => '/api/unfollowuser',
            '/api/Chataction' => '/api/chataction',
            '/api/SafetyTips' => '/api/safety-tips',
            '/api/Editprofile' => '/api/editprofile',
            '/api/Mypromotions' => '/api/mypromotions',
            '/api/Uploadimage' => '/api/uploadimage',
            '/api/buynowPayment' => '/api/buynow-payment',
            '/api/BuynowPayment' => '/api/buynow-payment',
            '/api/buynowpayment' => '/api/buynow-payment',
            '/api/Sendofferreq' => '/api/sendofferreq',
            '/api/getItems/' => '/api/getitems',
            '/api/getitemsnew/' => '/api/getitemsnew',
            '/api/Itemlike/' => '/api/itemlike',
            '/api/Checkpromotion/' => '/api/checkpromotion',
            '/api/Getfollowerid' => '/api/getfollowerid',
            'api/Getlikedid' => 'api/getliked-id',
            'api/getlikedid' => 'api/getliked-id',
            'api/getlikedId' => 'api/getliked-id',
            'api/checkItemstatus' => 'api/check-itemstatus',
            'api/getShippingAddress' => 'api/get-shipping-address',
            'api/GetShippingAddress' => 'api/get-shipping-address',
            'api/Addshipping' => 'api/addshipping',
            'api/braintreeClientToken' => 'api/braintree-client-token',
            'api/processingPayment' => 'api/processing-payment',
            'api/getinsights' => 'api/getinsights',
            'api/getuserproducts' => 'api/getuserproducts',
            'api/userproducts' => 'api/userproducts',
            '/api/forgetpassword/' => '/api/forgetpassword',
            '<controller:(api)>/<action:\w+>' => '<controller>/<action>',
            'buynow/addshipping' => '/buynow/addshipping',
            '<controller:\w+>/<id:\w+>' => '<controller>/view',
            '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
            '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            '<controller:(user)>/<action:(profiles)>/<id:\w+>' => '<controller>/<action>',

          ],
        ],
        'i18nJs' => [
          'class' => 'w3lifer\yii2\I18nJs',
        ],

      ],
      'params' => $params,
    ];