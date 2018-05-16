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
