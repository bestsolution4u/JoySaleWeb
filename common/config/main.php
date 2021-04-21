<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'Yii2Twilio' => [
        'class' => 'filipajdacic\yiitwilio\YiiTwilio',
        'account_sid' => 'AC4ad687dfd9be5cfb589a757a7c23ccce',
        'auth_key' => '713148c934af9d8a91bfa28781ddddd9', 
    ],
    ],
];
