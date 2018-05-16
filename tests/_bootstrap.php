<?php
/**
 * PHPUnit plugin for HiDev.
 *
 * @link      https://github.com/hiqdev/hidev-phpunit
 * @package   hidev-phpunit
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

Yii::setAlias('@hiqdev/yii2-daterangepicker', dirname(__DIR__) . '/src');
Yii::setAlias('@hiqdev/yii2-daterangepicker/tests', __DIR__);

$config = \yii\helpers\ArrayHelper::merge(
    require \hiqdev\composer\config\Builder::path('common'),
    require \hiqdev\composer\config\Builder::path('tests')
);

Yii::$app = new \yii\web\Application($config);
