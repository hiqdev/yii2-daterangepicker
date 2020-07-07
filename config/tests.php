<?php

return [
    'id' => 'testapp',
    'basePath' => dirname(__DIR__),
    'vendorPath' => dirname(__DIR__) . "/vendor",
    'aliases' => [
        '@bower' => '@vendor/bower-asset'
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'wefJDF8sfdsfSDefwqdxj9oq',
            'scriptFile' => dirname(__DIR__) .'/tests/index.php',
            'scriptUrl' => '/index.php',
        ],
        'assetManager' => [
            'basePath' => dirname(__DIR__) . '/tests/_output',
            'baseUrl' => '/',
        ],
    ],
];
