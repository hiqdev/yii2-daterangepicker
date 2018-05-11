<?php

namespace hiqdev\yii2\daterangepicker;

use hiqdev\yii2\daterangepicker\DateRangePickerAsset;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\FormatConverter;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

/**
 * Class DateRangePicker is a wrapper for a [DateRagePicker](http://www.daterangepicker.com/) JS plugin
 *
 * @author Pavel Agalecky <pavel.agalecky@gmail.com>
 * @author Dmitry Naumenko <d.naumenko.a@gmail.com>
 */
class DateRangePicker extends InputWidget
{
    /**
     * @var string the default format string to be used to format a date.
     * It can be a custom format as specified in the [ICU manual](http://userguide.icu-project.org/formatparse/datetime#TOC-Date-Time-Format-Syntax).
     * Alternatively this can be a string prefixed with `php:` representing a format that can be recognized by the
     * PHP [date()](http://php.net/manual/en/function.date.php)-function.
     * Alternatively this can be a string prefixed with `moment:` representing a format that can be recognized by the
     * moment.js [longDateFormat](http://momentjs.com/docs/#/customization/long-date-formats/) function.
     *
     * For example:
     *
     * ```php
     * 'MM/dd/yyyy' // date in ICU format
     * 'php:m/d/Y' // the same date in PHP format
     * 'moment:L' // the same date, but format will be received from moment.js
     * ```
     *
     * When is not set, will be provided by `moment.js` client library.
     */
    public $dateFormat;
    /**
     * @var string
     */
    public $separator = ' - ';
    /**
     * @var bool
     */
    public $timePicker = false;
    /**
     * @var bool
     */
    public $timePicker12Hour = false;
    /**
     * @var array
     */
    public $defaultRanges = true;
    /**
     * @var string
     */
    public $language;
    /**
     * @var array the options for the underlying js widget.
     */
    public $clientOptions = [];
    /**
     * @var array the events for the underlying js widget
     */
    public $clientEvents = [];
    /**
     * @inheritdoc
     * The following options are specially handled:
     *  - `tag`: the tag name. Set to `false` in order to prevent input render. When the property is not set, widget
     * will render the `<input>` tag.
     */
    public $options = [];
    /**
     * @var string the model attribute that this widget is associated with.
     */
    public $attribute2 = null;

    public function init()
    {
        parent::init();
        if ($this->language === null) {
            $this->language = Yii::$app->language;
        }
        if (!isset($this->dateFormat)) {
            $this->dateFormat = 'moment:L';
        }
    }


    public function run()
    {
        echo $this->renderInput() . "\n";

        $this->setupRanges();
        $this->localize();

        $this->registerClientScript();
    }

    /**
     * Registers the assets
     * @void
     * @throws InvalidConfigException
     */
    protected function registerAssets()
    {
        DateRangePickerAsset::register($this->view);
    }

    /**
     * @return string
     */
    protected function renderInput()
    {
        $options = $this->options;

        if (isset($options['tag'])) {
            if ($options['tag'] === false) {
                return '';
            }

            $tag = ArrayHelper::remove($options, 'tag');
            $value = $this->hasModel() ? Html::getAttributeValue($this->model, $this->attribute) : $this->value;
            return Html::tag($tag, $value, $options);
        } else {
            if ($this->hasModel() && $this->attribute2) {
                $result = Html::textInput('date-picker', '', $options)."\n";
                $options['type'] = 'hidden';
                $result .= Html::activeTextInput($this->model, $this->attribute, $options)."\n";
                $result .= Html::activeTextInput($this->model, $this->attribute2, $options);

                return $result;
            } elseif ($this->hasModel()) {
                return Html::activeTextInput($this->model, $this->attribute, $options);
            } else {
                return Html::textInput($this->name, $this->value, $options);
            }
        }
    }

    /**
     * Automatically convert the date format from PHP DateTime to Moment.js DateTime format
     * as required by bootstrap-daterangepicker plugin.
     *
     * @see http://php.net/manual/en/function.date.php
     * @see http://momentjs.com/docs/#/parsing/string-format/
     *
     * @param string $format the PHP date format string
     *
     * @return string
     * @author Kartik Visweswaran, Krajee.com, 2014
     */
    protected static function convertDateFormat($format)
    {
        return strtr($format, [
            // meridian lowercase remains same
            // 'a' => 'a',
            // meridian uppercase remains same
            // 'A' => 'A',
            // second (with leading zeros)
            's' => 'ss',
            // minute (with leading zeros)
            'i' => 'mm',
            // hour in 12-hour format (no leading zeros)
            'g' => 'h',
            // hour in 12-hour format (with leading zeros)
            'h' => 'hh',
            // hour in 24-hour format (no leading zeros)
            'G' => 'H',
            // hour in 24-hour format (with leading zeros)
            'H' => 'HH',
            //  day of the week locale
            'w' => 'e',
            //  day of the week ISO
            'W' => 'E',
            // day of month (no leading zero)
            'j' => 'D',
            // day of month (two digit)
            'd' => 'DD',
            // day name short
            'D' => 'DDD',
            // day name long
            'l' => 'DDDD',
            // month of year (no leading zero)
            'n' => 'M',
            // month of year (two digit)
            'm' => 'MM',
            // month name short
            'M' => 'MMM',
            // month name long
            'F' => 'MMMM',
            // year (two digit)
            'y' => 'YY',
            // year (four digit)
            'Y' => 'YYYY',
            // unix timestamp
            'U' => 'X',
        ]);
    }

    protected function setupRanges()
    {
        if ($this->defaultRanges && ArrayHelper::getValue($this->clientOptions, 'ranges') === null) {
            $this->clientOptions['ranges'] = [
                Yii::t('hiqdev.daterangepicker', 'Today', [], $this->language) => new JsExpression('[new Date(), new Date()]'),
                Yii::t('hiqdev.daterangepicker', 'Yesterday', [], $this->language) => new JsExpression('[moment().subtract(1, "days"), moment().subtract(1, "days")]'),
                Yii::t('hiqdev.daterangepicker', 'Last 7 Days', [], $this->language) => new JsExpression('[moment().subtract(6, "days"), new Date()]'),
                Yii::t('hiqdev.daterangepicker', 'Last 30 Days', [], $this->language) => new JsExpression('[moment().subtract(29, "days"), new Date()]'),
                Yii::t('hiqdev.daterangepicker', 'This Month', [], $this->language) => new JsExpression('[moment().startOf("month"), moment().endOf("month")]'),
                Yii::t('hiqdev.daterangepicker', 'Last Month', [], $this->language) => new JsExpression('[moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]'),
            ];
        }
    }

    protected function localize()
    {
        if (strncmp($this->dateFormat, 'php:', 4) === 0) {
            $format = $this->convertDateFormat(substr($this->dateFormat, 4));
        } elseif (strncmp($this->dateFormat, 'moment:', 7) === 0) {
            $format = Json::encode(substr($this->dateFormat, 7));
            $format = new JsExpression("moment.localeData().longDateFormat(" . $format . ")");
        } else {
            $format = $this->convertDateFormat(FormatConverter::convertDateIcuToPhp($this->dateFormat, 'datetime', $this->language));
        }

        $this->clientOptions['locale'] = [
            'format' => $format,
            'applyLabel' => Yii::t('hiqdev.daterangepicker', 'Apply', [], $this->language),
            'cancelLabel' => Yii::t('hiqdev.daterangepicker', 'Cancel', [], $this->language),
            'fromLabel' => Yii::t('hiqdev.daterangepicker', 'From', [], $this->language),
            'toLabel' => Yii::t('hiqdev.daterangepicker', 'To', [], $this->language),
            'weekLabel' => Yii::t('hiqdev.daterangepicker', 'W', [], $this->language),
            'customRangeLabel' => Yii::t('hiqdev.daterangepicker', 'Custom Range', [], $this->language),
        ];
    }

    /**
     * Registers a specific jQuery UI widget options
     * @throws InvalidConfigException
     */
    protected function registerClientScript()
    {
        $this->registerAssets();

        $id = isset($this->options['id']) ? $this->options['id'] : $this->getId();

        $options = ArrayHelper::merge([
            'timePicker' => $this->timePicker,
            'timePicker12Hour' => $this->timePicker12Hour,
            'separator' => $this->separator,
            'autoUpdateInput' => false,
        ], $this->clientOptions);

        $this->getView()->registerJs("$('#$id').daterangepicker(" . Json::encode($options) . ');');

        if ($this->attribute2) {
            $from = $this->model->attributes[$this->attribute];
            $till = $this->model->attributes[$this->attribute2];
            $js = "$('#$id').val('" . ($from && $till ? $from . $this->separator . $till : "") . "');";
            $this->getView()->registerJs($js);

            if (!array_key_exists('apply.daterangepicker', $this->clientEvents)) {
                $this->clientEvents['apply.daterangepicker'] = new JsExpression(/** @lang JavaScript */
                    "function (event, picker) {
                        var form = $(picker.element[0]).closest('form');
                        var start = picker.startDate.format('{$this->dateFormat}'.toUpperCase());
                        var end = picker.endDate.format('{$this->dateFormat}'.toUpperCase());
                        
                        $('#$id').val(start + '{$this->separator}' + end);
                        form.find(\"input[name*='{$this->attribute}']\").val(start);
                        form.find(\"input[name*='{$this->attribute2}']\").val(end);
                    }");
            }
            if (!array_key_exists('cancel.daterangepicker', $this->clientEvents)) {
                $this->clientEvents['cancel.daterangepicker'] = new JsExpression(/** @lang JavaScript */
                    "function (event, picker) {
                        var form = $(picker.element[0]).closest('form');

                        $('#$id').val('');
                        form.find(\"input[name *= '{$this->attribute}']\").val('');
                        form.find(\"input[name *= '{$this->attribute2}']\").val('');
                    }");
            }
        }
        if ($this->clientEvents) {
            $js = "$('#$id')";
            foreach ($this->clientEvents as $event => $handler) {
                $js .= ".on('$event', $handler)";
            }
            $this->getView()->registerJs($js);
        }
    }
}
