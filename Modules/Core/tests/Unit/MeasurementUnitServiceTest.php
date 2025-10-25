<?php

namespace Modules\Core\Tests\Unit;

use Modules\Core\Services\MeasurementUnitService;
use Tests\TestCase;

class MeasurementUnitServiceTest extends TestCase
{
    public function test_weight_conversions(): void
    {
        $this->assertEquals(1.0, MeasurementUnitService::convert(1000, 'mg', 'g'));
        $this->assertEquals(1000.0, MeasurementUnitService::convert(1, 'g', 'mg'));
        $this->assertEquals(0.5, MeasurementUnitService::convert(500, 'mg', 'g'));
    }

    public function test_unit_conversions(): void
    {
        $this->assertEquals(1000.0, MeasurementUnitService::convert(1, 'TU', 'U'));
        $this->assertEquals(2.5, MeasurementUnitService::convert(2500, 'U', 'TU'));
    }

    public function test_invalid_conversion_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        MeasurementUnitService::convert(100, 'mg', 'ml');
    }

    public function test_unit_categories(): void
    {
        $weightUnits = MeasurementUnitService::getWeightUnits();
        $this->assertTrue($weightUnits->has('mg'));
        $this->assertTrue($weightUnits->has('g'));
        $this->assertFalse($weightUnits->has('ml'));
    }
}
