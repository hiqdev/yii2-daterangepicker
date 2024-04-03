<?php
/**
 * Date Range Picker widget for Yii2
 *
 * @link      https://github.com/hiqdev/yii2-daterangepicker
 * @package   yii2-daterangepicker
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\yii2\daterangepicker;

use yii\web\AssetBundle;

/**
 * Class DateRangePickerAsset.
 */
class DateRangePickerAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap-daterangepicker';
    public $js = ['daterangepicker.js'];
    public $css = ['daterangepicker.css'];
    public $depends = [
        'omnilight\assets\MomentAsset',
        'omnilight\assets\MomentLanguageAsset',
        'yii\web\JqueryAsset',
    ];
    public $publishOptions = [
        'only' => ['daterangepicker.css', 'daterangepicker.js'],
    ];
}
