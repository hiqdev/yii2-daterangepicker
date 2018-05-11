<?php

namespace hiqdev\yii2\daterangepicker;

use yii\web\AssetBundle;

/**
 * Class DateRangePickerAsset
 */
class DateRangePickerAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap-daterangepicker';

    public $js = [
        'daterangepicker.js'
    ];

    public $css = [
        'daterangepicker.css'
    ];

    public $depends = [
        'omnilight\assets\MomentAsset',
        'omnilight\assets\MomentLanguageAsset',
        'yii\web\JqueryAsset',
    ];
}
