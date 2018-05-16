<?php

namespace hiqdev\yii2\daterangepicker\tests\unit;

use hipanel\modules\client\models\ClientSearch;
use hiqdev\yii2\daterangepicker\DateRangePicker;
use hiqdev\yii2\daterangepicker\tests\mock\SearchModel;

class DateRangePickerTest extends \PHPUnit\Framework\TestCase
{
    public function testRenderWithModel()
    {
        $model = new SearchModel();
        $expected = '<input type="text" id="searchmodel-create_time_ge" class="form-control" name="date-picker" value="">
<input type="hidden" id="searchmodel-create_time_ge" class="form-control" name="SearchModel[create_time_ge]">
<input type="hidden" id="searchmodel-create_time_ge" class="form-control" name="SearchModel[create_time_lt]">
';
        $output = DateRangePicker::widget([
            'model' => $model,
            'attribute' => 'create_time_ge',
            'attribute2' => 'create_time_lt',
            'options' => [
                'class' => 'form-control',
            ],
            'dateFormat' => 'yyyy-MM-dd',
        ]);
        $this->assertEquals($expected, $output);
    }
}
