<?php
/**
 * Flora Point - ЛК
 */

use kartik\mpdf\Pdf;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => '359_floraPoint_lk',
    'name' => 'FloraPoint',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'layout' => 'user',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
            'layout' => 'admin',
        ],
        'debug' => [
            'panels' => [
                'httpclient' => [
                    'class' => 'yii\\httpclient\\debug\\HttpClientPanel',
                ],
            ],
        ],
    ],
    'components' => [
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'locale' => 'ru_RU',
            'timeZone' => 'Europe/Moscow',
            'dateFormat' => 'dd.MM.yyyy',
            'timeFormat' => 'H:mm:ss',
            'datetimeFormat' => 'dd.MM.yyyy H:mm:ss',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'RUB',
        ],
        'request' => [
            'baseUrl' => '',
            'cookieValidationKey' => 'lsud8fs7dfhlk2jdfh33ios6audsf6us78oiuharl4hrkljsdhf',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\db\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'viewPath' => '@app/mail',
            'htmlLayout' => 'layouts/main-html',
            'textLayout' => 'layouts/main-text',
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => ['info@lk.florapoint.ru' => 'Florapoint.ru'],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_GET', '_POST'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'categories' => ['cronWork'],
                    'levels' => ['info', 'profile', 'error', 'warning'],
                    'logFile' => '@runtime/logs/'. date('d.m.Y') . '.log',
                    'logVars' => ['_GET', '_POST'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'user/payment/deposit_<d:\d+>/invoice_<i:\d+>/history_<h:\d+>' => 'user/payment',
                'user/download-act/id_<id:\d+>' => 'user/download-act',
                'user/download-pdf/id_<id:\d+>/number_<invoice_number:\d+>' => 'user/download-pdf',
                'user/success-payment/transaction_<transaction_id:\d+>' => 'user/success-payment',
                'user/false-payment/transaction__<transaction_id:\d+>' => 'user/false-payment',

                'user/tickets/tickets_<id:\d+>' => 'user/tickets',
                'user/tickets' => 'user/tickets',
            ],
        ],
        'pdf' => [
            'class' => Pdf::class,
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_FILE
        ]
    ],
    'controllerMap' => [
        'elfinder' => [
            'class' => 'mihaildev\elfinder\PathController',
            'access' => ['@'],
            'root' => [
                'path' => 'upload',
                'name' => 'files'
            ],
        ]
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '46.147.255.241'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '46.147.255.241'],
    ];
}

return $config;
