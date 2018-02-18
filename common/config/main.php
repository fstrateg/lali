<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'timeZone'=>'Asia/Bishkek',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'telegram' =>
            [
                'class'=>'common\components\Telegram',
                'apiUrl'=>'https://api.telegram.org/',
                'token'=>'405775967:AAFNlCKgAhRUo_BH8nLV8IlegnjpNNBX1xk',
            ],
        'yclients' =>
        [
            'class'=>'common\components\Yclients',
            'token'=>'jw9gng6r8kggd54wdgh3',
            'user'=>'e5638093ec3d59085e53012f6ac163e6',
            'company'=>'31224',
        ]
    ],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
];
