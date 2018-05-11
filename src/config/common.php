<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

return [
    'components' => [
        'i18n' => [
            'class' => \hipanel\components\I18N::class,
            'translations' => [
                'daterangepicker' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hiqdev/yii2/daterangepicker/messages',
                    'sourceLanguage' => 'en-US',
                ],
            ],
        ],
    ]
];
