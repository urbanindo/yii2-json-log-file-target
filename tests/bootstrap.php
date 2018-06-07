<?php
require dirname(__FILE__) . '/../vendor/autoload.php';
require dirname(__FILE__) . '/../vendor/yiisoft/yii2/Yii.php';


$config = [
    'id' => 'Yii2 JsonLogFileTarget Test',
    'basePath' => dirname(__FILE__),
    'components' => [
        'db' => [
            'class' => '\yii\db\Connection',
            'dsn' => 'sqlite::memory:',
        ],
    ]
];

$application = new yii\console\Application($config);