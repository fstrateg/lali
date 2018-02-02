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
        'telegram' =>
            [
                'class'=>'common\components\Telegram',
                'apiUrl'=>'https://api.telegram.org/',
                'token'=>'405775967:AAFNlCKgAhRUo_BH8nLV8IlegnjpNNBX1xk',
            ],
    ],
];
