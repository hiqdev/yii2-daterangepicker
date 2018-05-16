# Yii2 Date Range Picker

**Date Range Picker widget for Yii2**

[![Latest Stable Version](https://poser.pugx.org/hiqdev/yii2-daterangepicker/v/stable)](https://packagist.org/packages/hiqdev/yii2-daterangepicker)
[![Total Downloads](https://poser.pugx.org/hiqdev/yii2-daterangepicker/downloads)](https://packagist.org/packages/hiqdev/yii2-daterangepicker)
[![Build Status](https://img.shields.io/travis/hiqdev/yii2-daterangepicker.svg)](https://travis-ci.org/hiqdev/yii2-daterangepicker)
[![Scrutinizer Code Coverage](https://img.shields.io/scrutinizer/coverage/g/hiqdev/yii2-daterangepicker.svg)](https://scrutinizer-ci.com/g/hiqdev/yii2-daterangepicker/)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/hiqdev/yii2-daterangepicker.svg)](https://scrutinizer-ci.com/g/hiqdev/yii2-daterangepicker/)
[![Dependency Status](https://www.versioneye.com/php/hiqdev:yii2-daterangepicker/dev-master/badge.svg)](https://www.versioneye.com/php/hiqdev:yii2-daterangepicker/dev-master)

This widget renders a DateRangePicker input control using [Bootstrap Date Range Picker] widget.

[Bootstrap Date Range Picker]: http://www.daterangepicker.com/

## Installation

The preferred way to install this yii2-extension is through [composer](http://getcomposer.org/download/).

Either run

```sh
php composer.phar require "hiqdev/yii2-daterangepicker"
```

or add

```json
"hiqdev/yii2-daterangepicker": "*"
```

to the require section of your composer.json.

## Usage

There are two ways of using this widget: with an `ActiveForm` instance or
as a widget setting up its `model` and `attribute`.
Additional [options] could be passed in `'clientOptions'` array.

[options]: http://www.daterangepicker.com/#options

### With an ActiveForm

```php
<?php
use hipanel\widgets\DatePicker;
...
$this->pickerOptions = ArrayHelper::merge([
    'class' => DateRangePicker::class,
    'name' => '',
    'options' => [
        'tag' => false,
        'id' => "{$id}-period-btn",
    ],
    'clientEvents' => [
        'apply.daterangepicker' => new JsExpression(/** @lang JavaScript */"
            function (event, picker) {
                var form = $(picker.element[0]).closest('form');
                var span = form.find('#{$id}-period-btn span');

                span.text(picker.startDate.format('ll') + ' - ' + picker.endDate.format('ll'));

                form.find('input[name=from]').val(picker.startDate.format());
                form.find('input[name=till]').val(picker.endDate.format());
                form.trigger('change.updateChart');
            }
        "),
        'cancel.daterangepicker' => new JsExpression(/** @lang JavaScript */"
            function (event, picker) {
                var form = $(event.element[0]).closest('form');
                var span = form.find('#{$id}-period-btn span');

                span.text(span.data('prompt'));

                form.find('input[name=from]').val('');
                form.find('input[name=till]').val('');
                form.trigger('change.updateChart');
            }
        "),
    ],
    'clientOptions' => [
        'ranges' => [
            Yii::t('hipanel', 'Current Month') => new JsExpression('[moment().startOf("month"), new Date()]'),
            Yii::t('hipanel', 'Previous Month') => new JsExpression('[moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]'),
            Yii::t('hipanel', 'Last 3 months') => new JsExpression('[moment().subtract(3, "month").startOf("month"), new Date()]'),
            Yii::t('hipanel', 'Last year') => new JsExpression('[moment().subtract(1, "year").startOf("year"), new Date()]'),
        ],
    ],
], $this->pickerOptions);
...
?>
```

### As a widget

```php
<?php
use hipanel\widgets\DatePicker;
?>

<?= DateRangePicker::widget([
    'model' => $search->model,
    'attribute' => 'create_from',
    'attribute2' => 'create_till',
    'options' => [
        'class' => 'form-control',
    ],
    'dateFormat' => 'yyyy-MM-dd',
]) ?>
```

## License

This project is released under the terms of the BSD-3-Clause [license](LICENSE).
Read more [here](http://choosealicense.com/licenses/bsd-3-clause).

Copyright © 2018, HiQDev (http://hiqdev.com/)

## Acknowledgments

This package is based on [omnilight/yii2-bootstrap-daterangepicker].

[omnilight/yii2-bootstrap-daterangepicker]: https://github.com/omnilight/yii2-bootstrap-daterangepicker
