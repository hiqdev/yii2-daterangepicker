<?php

return [
    'id' => 'testapp',
    'basePath' => dirname(__DIR__, 2),
    'vendorPath' => dirname(__DIR__, 2) . "/vendor",
    'aliases' => [
        '@bower' => '@vendor/bower-asset'
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'wefJDF8sfdsfSDefwqdxj9oq',
            'scriptFile' => dirname(__DIR__, 2) .'/tests/index.php',
            'scriptUrl' => '/index.php',
        ],
        'assetManager' => [
            'basePath' => '@hiqdev/yii2-daterangepicker/tests/_output',
            'baseUrl' => '/',
        ],
    ],
];
