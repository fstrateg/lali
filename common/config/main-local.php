<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=lalettyk_db',
            'username' => 'luser',
            'password' => '!Qaz@Wsx#Edc123',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
        'gii' => [
            'class'=>'yii\gii\Module',
        ]
    ],
];
