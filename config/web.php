<?php



$params = require(__DIR__ . '/params.php');

$config = [
    'layout'=>'smartAdminN',
    'id' => 'basic',
    'name' => 'Aser',
    'basePath' => dirname(__DIR__),
    // set target language to be Russian
    'language' => 'ru-RU',
    // set source language to be English
    'sourceLanguage' => 'en-US',
    'bootstrap' => ['log', 'globalControllerComponent', 'maintenanceMode', /*'gii', 'debug'*/],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\AdminModule',
        ],
        'mount' => [
            'class' => 'app\modules\mount\Mount',
        ],
        'prom' => [
            'class' => 'app\modules\prom\Module',
        ],
        'metrix' => [
            'class' => 'app\modules\metrix\Metrix',
        ],
        'api' => [
            'class' => 'app\modules\api\Module',
            'modules' => [
                'v1' => [
                    'class' => 'app\modules\api\v1\Module',
                ],
            ],
        ],
        'counter' => [
            'class' => 'app\modules\counter\Module',
        ],

        'gii' => 'yii\gii\Module',
        /*
       'debug' => [
           'class' => 'yii\debug\Module',
           'allowedIPs' => ['*']
       ]*/


    ],
    'components' => [
        'maintenanceMode'=>[
            'class' => '\brussens\maintenance\MaintenanceMode',
            'enabled'=>false,
            // Route to action
            'route'=>'maintenance/index',
            // Show message
            'message'=>'Ведутся технические работы по обновлению системы. По всем вопросам обращайтесь по телефону
            <a href="tel:380487702487">048 770-24-87</a> или на почту <a href="mailto:support@aser.com.ua">support@aser.com.ua</a>',
            // Allowed user names
//            'users'=>[
//                'admin@admin.com',
//            ],

            // Allowed roles
//            'roles'=>[
//                'admin',
//            ],

            // Allowed IP addresses
            'ips'=>[
                '127.0.0.1',
                //office
                '195.138.89.253',
                //Igor
                '85.238.104.26'
            ],

            // Allowed URLs
//            'urls'=>[
//                'site/login'
//            ],

//             Layout path
            'layoutPath'=>'@app/views/maintenance/layouts/main',

            // View path
            'viewPath'=>'@app/views/maintenance/views/index',

            // User name attribute name
            'usernameAttribute'=>'login',

            // HTTP Status Code
            'statusCode'=>503,
        ],
        'bot' => [
            'class' => 'SonkoDmitry\Yii\TelegramBot\Component',
            'apiToken' => '156615567:AAHiMjrT4Qdvdei68hvTC0sX25ZpuuXe2J0',
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
        'globalControllerComponent' => [
            'class' => 'app\components\GlobalControllerComponent',
        ],
        'assetManager' => [
            'linkAssets' => true,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'enableCookieValidation' => true,
            'cookieValidationKey' => 'DM-tyKkYBNrIzM7VvLOVU3mEUGRSW0Fg',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'i18n' => [
            // set target language to be Russian

            // set source language to be English

            'translations' => [
                'language' => 'ru-RU',
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'prom' => 'prom.php',
                        'counter' => 'counter.php',
                    ],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'class'=>'app\components\LanguageUrlManager',
            'rules' => [
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
                //@TODO - Add new API calls there
                ['class' => 'yii\rest\UrlRule', 'controller' => ['api/v1/user-counter']],
                ['class' => 'yii\rest\UrlRule', 'controller' => ['api/v1/counter-address']],
                ['class' => 'yii\rest\UrlRule', 'controller' => ['api/v1/counter-models']],
                ['class' => 'yii\rest\UrlRule', 'controller' => ['api/v1/csv-token']],
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
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'mobileDetect' => [
            'class' => '\ezze\yii2\mobiledetect\MobileDetect'
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
        'db' => require(__DIR__ . '/db.php'),
      // 'db2' => require(__DIR__ . '/db2.php'),
    ],
    'params' => $params,
];


//@TODO - add DEV ENV
//$config['bootstrap'][] = 'debug';
//$config['modules']['debug'] = [
//    'class' => 'yii\debug\Module',
//    'allowedIPs' => ['*']
//];


//$config['bootstrap'][] = 'gii';
//$config['modules']['gii'] = 'yii\gii\Module';


return $config;
