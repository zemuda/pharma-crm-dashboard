<?php

namespace Modules\Core\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;

// class MeasurementUnitService
// {
//     public function handle() {}
// }

class MeasurementUnitService
{
    protected static array $units = [
        // Weight units
        [
            'abbreviation' => 'g',
            'name' => 'gram',
            'conversion_to_base' => 1.0,
            'base_unit' => 'g',
            'category' => 'weight',
            'is_si_unit' => true,
            'precision' => 3,
        ],
        [
            'abbreviation' => 'mg',
            'name' => 'milligram',
            'conversion_to_base' => 0.001,
            'base_unit' => 'g',
            'category' => 'weight',
            'is_si_unit' => true,
            'precision' => 2,
        ],
        [
            'abbreviation' => 'mcg',
            'name' => 'microgram',
            'conversion_to_base' => 0.000001,
            'base_unit' => 'g',
            'category' => 'weight',
            'is_si_unit' => true,
            'precision' => 0,
        ],

        // Unit-based measurements
        [
            'abbreviation' => 'U',
            'name' => 'unit',
            'conversion_to_base' => 1.0,
            'base_unit' => 'U',
            'category' => 'units',
            'is_si_unit' => false,
            'precision' => 0,
        ],
        [
            'abbreviation' => 'TU',
            'name' => 'thousand units',
            'conversion_to_base' => 1000.0,
            'base_unit' => 'U',
            'category' => 'units',
            'is_si_unit' => false,
            'precision' => 2,
        ],
        [
            'abbreviation' => 'MU',
            'name' => 'million units',
            'conversion_to_base' => 1000000.0,
            'base_unit' => 'U',
            'category' => 'units',
            'is_si_unit' => false,
            'precision' => 3,
        ],

        // Volume units
        [
            'abbreviation' => 'ml',
            'name' => 'milliliter',
            'conversion_to_base' => 1.0,
            'base_unit' => 'ml',
            'category' => 'volume',
            'is_si_unit' => true,
            'precision' => 2,
        ],
        [
            'abbreviation' => 'L',
            'name' => 'liter',
            'conversion_to_base' => 1000.0,
            'base_unit' => 'ml',
            'category' => 'volume',
            'is_si_unit' => true,
            'precision' => 3,
        ],
        [
            'abbreviation' => 'tsp',
            'name' => 'teaspoon',
            'conversion_to_base' => 4.92892,
            'base_unit' => 'ml',
            'category' => 'volume',
            'is_si_unit' => false,
            'precision' => 1,
        ],
        [
            'abbreviation' => 'tbsp',
            'name' => 'tablespoon',
            'conversion_to_base' => 14.7868,
            'base_unit' => 'ml',
            'category' => 'volume',
            'is_si_unit' => false,
            'precision' => 1,
        ],

        // Molecular units
        [
            'abbreviation' => 'mmol',
            'name' => 'millimole',
            'conversion_to_base' => 1.0,
            'base_unit' => 'mmol',
            'category' => 'molecular',
            'is_si_unit' => true,
            'precision' => 3,
        ],

        // Concentration units
        [
            'abbreviation' => 'mg/ml',
            'name' => 'milligrams per milliliter',
            'conversion_to_base' => 1.0,
            'base_unit' => 'mg/ml',
            'category' => 'concentration',
            'is_si_unit' => true,
            'precision' => 3,
        ],
        [
            'abbreviation' => 'mcg/ml',
            'name' => 'micrograms per milliliter',
            'conversion_to_base' => 0.001,
            'base_unit' => 'mg/ml',
            'category' => 'concentration',
            'is_si_unit' => true,
            'precision' => 2,
        ],
        [
            'abbreviation' => '%',
            'name' => 'percent',
            'conversion_to_base' => 10.0, // 1% = 10 mg/ml
            'base_unit' => 'mg/ml',
            'category' => 'concentration',
            'is_si_unit' => false,
            'precision' => 2,
        ],
    ];

    public static function all(): Collection
    {
        return Cache::remember('pharmacy_units', 3600, function () {
            return collect(self::$units)->keyBy('abbreviation');
        });
    }

    public static function findByAbbreviation(string $abbreviation): ?array
    {
        return self::all()->get($abbreviation);
    }

    public static function getByCategory(string $category): Collection
    {
        return self::all()->where('category', $category);
    }

    public static function getWeightUnits(): Collection
    {
        return self::getByCategory('weight');
    }

    public static function getVolumeUnits(): Collection
    {
        return self::getByCategory('volume');
    }

    public static function getUnitTypes(): Collection
    {
        return self::getByCategory('units');
    }

    public static function getConcentrationUnits(): Collection
    {
        return self::getByCategory('concentration');
    }

    public static function convert(float $value, string $fromUnit, string $toUnit): float
    {
        $from = self::findByAbbreviation($fromUnit);
        $to = self::findByAbbreviation($toUnit);

        if (!$from) {
            throw new InvalidArgumentException("Source unit '{$fromUnit}' not found");
        }

        if (!$to) {
            throw new InvalidArgumentException("Target unit '{$toUnit}' not found");
        }

        if ($from['category'] !== $to['category']) {
            throw new InvalidArgumentException(
                "Cannot convert between {$from['category']} ({$fromUnit}) and {$to['category']} ({$toUnit})"
            );
        }

        $baseValue = $value * $from['conversion_to_base'];
        $converted = $baseValue / $to['conversion_to_base'];

        return round($converted, $to['precision']);
    }

    public static function convertWithPrecision(float $value, string $fromUnit, string $toUnit, ?int $precision = null): float
    {
        $converted = self::convert($value, $fromUnit, $toUnit);
        $toUnitData = self::findByAbbreviation($toUnit);

        $finalPrecision = $precision ?? $toUnitData['precision'] ?? 3;
        return round($converted, $finalPrecision);
    }

    public static function addCustomUnit(array $unit): void
    {
        // Validate required fields
        $required = ['abbreviation', 'name', 'conversion_to_base', 'base_unit', 'category'];
        foreach ($required as $field) {
            if (!isset($unit[$field])) {
                throw new InvalidArgumentException("Missing required field: {$field}");
            }
        }

        self::$units[] = array_merge([
            'is_si_unit' => false,
            'precision' => 3,
        ], $unit);

        Cache::forget('pharmacy_units');
    }

    public static function validateConversion(string $fromUnit, string $toUnit): bool
    {
        $from = self::findByAbbreviation($fromUnit);
        $to = self::findByAbbreviation($toUnit);

        return $from && $to && $from['category'] === $to['category'];
    }

    public static function getConversionFactor(string $fromUnit, string $toUnit): float
    {
        if (!self::validateConversion($fromUnit, $toUnit)) {
            throw new InvalidArgumentException("Invalid conversion from {$fromUnit} to {$toUnit}");
        }

        $from = self::findByAbbreviation($fromUnit);
        $to = self::findByAbbreviation($toUnit);

        return ($from['conversion_to_base'] / $to['conversion_to_base']);
    }

    public static function getBaseUnit(string $unit): ?string
    {
        $unitData = self::findByAbbreviation($unit);
        return $unitData['base_unit'] ?? null;
    }

    public static function formatValue(float $value, string $unit, ?int $precision = null): string
    {
        $unitData = self::findByAbbreviation($unit);
        $finalPrecision = $precision ?? $unitData['precision'] ?? 3;

        $formattedValue = number_format($value, $finalPrecision);
        return "{$formattedValue} {$unit}";
    }
}
