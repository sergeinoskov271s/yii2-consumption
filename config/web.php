<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
	'name'=>'Consumption App',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
	'language' => 'ru-RU',
	'timeZone' => 'Asia/Yekaterinburg',
    'components' => [
		'dateFormat' => 'yyyy-MM-dd',
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '665010',
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
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
//			'traceLevel' => 3,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','info'],
                ],
            ],
        ],
        'db' => $db, 
        'urlManager' => [			
            'enablePrettyUrl' => true,
            'showScriptName' => false,
			'enableStrictParsing' => false,
//			'showScriptName' => false, // not index
			'baseUrl' => '/yii2-consumption/web',
			'scriptUrl'=>'/yii2-consumption/web/index.php',
            'rules' => 
			[
				[
					'class' => 'app\components\OutlayUrlRule',
				],
				[
					'class' => 'app\components\BudgetUrlRule',
				],
				[
					'class' => 'app\components\TemplateUrlRule',
				],
				[
					'class' => 'app\components\AdminUrlRule',
				],
				'json/<controller:[A-Za-z0-9 -_.]+>/<action:\w+>/<id:\d+>' => 'json/<controller>/<action>',
				/* [
					'class' => 'app\components\JsonOutlayUrlRule',
				], */
				[
					'class' => 'app\components\JsonUrlRule',
				],
            ],
				
        ],
        
//		'class' => 'yii\web\UrlManager',
//        'urlManager' => [
//			'class' => 'yii\web\UrlManager',
 //           'enablePrettyUrl' => true,
//            'showScriptName' => false,
//			 'enableStrictParsing' => false,
//			'baseUrl' => '/yii2-consumption/web',
//			'scriptUrl'=>'/yii2-consumption/web/index.php',
//			'rules' => [
//				'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                 /*  [
                        'pattern' => 'admin/parse/(?P<action>[A-Za-z-]+)/(?P<pole>[A-Za-z-]+)',
                        'route' => 'admin/<controller>/<action>',
                        'suffix' => '/',
    				//	'normalizer' => true, // disable normalizer for this rule
                    ], */
				/*  [ 
					'pattern' => 'outlay',
					'route' => 'outlay/index',
					'suffix' => '/',
					'normalizer' => false, // disable normalizer for this rule
				],  */
				
				// [
					// 'pattern' => 'outlay/<action>/<id:\w+>',
					// 'route' => 'outlay/<action>',
					// 'defaults' => ['action' => 'index'],
					// 'class' => 'app\components\OutlayUrlRule',					
				// ], 
//				[
	//				'class' => 'app\components\OutlayUrlRule',
//				],
            //    'admin/parse/(?P<action>[A-Za-z-]+)/(?P<pole>[_A-Za-z-]+)(/(\S+))?' => 'admin/parse/<action>',
 //               'json/<controller:[A-Za-z0-9 -_.]+>/<action:\w+>/<id:\d+>' => 'json/<controller>/<action>',
				/*[
//					'class' => 'app\components\JsonDepartmentUrlRule',					
					'class' => 'app\components\JsonUrlRule',					
				],*/
//			]
//		],
 // ...
		'i18n' => [
			'translations' => [
				'app*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@app/messages',
					//'sourceLanguage' => 'en-US',
					'fileMap' => [
						'app' => 'app.php',
						'app/error' => 'error.php',
					],
				],
			],
		],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '192.168.1.5'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '192.168.1.5'],
    ];
}

return $config;
