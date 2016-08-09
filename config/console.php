<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');
Yii::setAlias('@runnerScript', dirname(dirname(dirname(__FILE__))) .'/yii');

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii', 'maintenanceMode'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],

    'controllerMap' => [
        'cron' => [
            'class' => 'denisog\cronjobs\CronController'
        ],
    ],
    'components' => [

        'maintenanceMode'=>[
            'class' => '\brussens\maintenance\MaintenanceMode',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
         'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            //'parsers' => [
            //    'application/json' => 'yii\web\JsonParser',
            //],
            //'enableCookieValidation' => true,
            //'cookieValidationKey' => 'DM-tyKkYBNrIzM7VvLOVU3mEUGRSW0Fg',
        ],

         'bot' => [
            'class' => 'SonkoDmitry\Yii\TelegramBot\Component',
            'apiToken' => '156615567:AAHiMjrT4Qdvdei68hvTC0sX25ZpuuXe2J0',
    ],
         'user' => [
             'class' => 'app\models\User',
           // 'identityClass' => 'app\models\User',
            //'enableAutoLogin' => true,
        ],

        'mail' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mail.ukraine.com.ua',
                'username' => 'noreply@aser.com.ua',
                'password' => 'A1CZ33Ilpbm7',
                'port' => '25',
                
            //'encryption' => 'tls',
            ],
        ],
        'urlManager' => [
            'baseUrl'=>'http://admin.aser.com.ua',
            'hostInfo'=>'http://admin.aser.com.ua/',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
                //@TODO - Add new API calls there
                ['class' => 'yii\rest\UrlRule', 'controller' => ['api/v1/user-counter']],
                ['class' => 'yii\rest\UrlRule', 'controller' => ['api/v1/counter-address']],
                ['class' => 'yii\rest\UrlRule', 'controller' => ['api/v1/counter-models']],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/v1/user-counter'],
                    //'pluralize' => false,
                    'extraPatterns' => [
                        'PUT edit' => 'edit-counter',
                        'GET img' => 'counter-image',
                    ],
                ],
            ]
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'dumper' => [
            'class' => 'app\components\dumpDB',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
];
