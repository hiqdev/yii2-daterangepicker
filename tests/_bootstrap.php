<?php
/**
 * PHPUnit plugin for HiDev.
 *
 * @link      https://github.com/hiqdev/hidev-phpunit
 * @package   hidev-phpunit
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

error_reporting(E_ALL & ~E_NOTICE);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

Yii::setAlias('@hiqdev/yii2-daterangepicker', dirname(__DIR__) . '/src');
Yii::setAlias('@hiqdev/yii2-daterangepicker/tests', __DIR__);

$config = \yii\helpers\ArrayHelper::merge(require \hiqdev\composer\config\Builder::path('common'), [
    'id' => 'testapp',
    'basePath' => dirname(__DIR__),
    'vendorPath' => dirname(__DIR__) . "/vendor",
    'aliases' => [
        '@bower' => '@vendor/bower-asset'
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'wefJDF8sfdsfSDefwqdxj9oq',
            'scriptFile' => __DIR__ .'/index.php',
            'scriptUrl' => '/index.php',
        ],
        'assetManager' => [
            'basePath' => '@hiqdev/yii2-daterangepicker/tests/_output',
            'baseUrl' => '/',
        ]
    ]
]);

Yii::$app = new \yii\web\Application($config);
