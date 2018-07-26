<?php

Yii::setAlias('@tests', dirname(__DIR__));

$config = [
    'id' => 'unit',
    'basePath' => Yii::getAlias('@tests'),
    'components' => [
        'db' => [
            'class'             => 'yii\db\Connection',
            'dsn'               => 'mysql:host=127.0.0.1;dbname=translation_behavior_yii2_tests',
            'username'          => 'root',
            'password'          => '',
            'emulatePrepare'    => true,
            'charset'           => 'utf8',
            'enableSchemaCache' => false
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ]
];

if (file_exists(__DIR__ . '/local/config.php')) {
    require_once __DIR__ . '/local/config.php';
}

return $config;
