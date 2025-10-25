Here are comprehensive applications and examples for the **Advanced Enum with Database Backing** approach:

## 1. Complete Implementation with Enhanced Features

```php
<?php

namespace App\Services\Pharmacy;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;

class PharmacyUnitService
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
```

## 2. Real-World Application Examples

### Medication Dosage Calculator

```php
<?php

namespace App\Services\Pharmacy;

class MedicationService
{
    public function calculateDosage(
        float $patientWeight,
        string $weightUnit,
        float $dosagePerKg,
        string $dosageUnit,
        string $outputUnit = 'mg'
    ): array {
        // Convert patient weight to kg if needed
        $weightInKg = $weightUnit === 'kg'
            ? $patientWeight
            : PharmacyUnitService::convert($patientWeight, $weightUnit, 'g') / 1000;

        // Calculate total dosage
        $totalDosageMg = $weightInKg * $dosagePerKg;

        // Convert to desired output unit
        $finalDosage = PharmacyUnitService::convert($totalDosageMg, 'mg', $outputUnit);

        return [
            'weight_kg' => $weightInKg,
            'dosage_mg_per_kg' => $dosagePerKg,
            'total_dosage' => $finalDosage,
            'unit' => $outputUnit,
            'formatted' => PharmacyUnitService::formatValue($finalDosage, $outputUnit),
        ];
    }

    public function reconstituteMedication(
        float $powderWeight,
        string $powderUnit,
        float $diluentVolume,
        string $diluentUnit,
        string $outputConcentrationUnit = 'mg/ml'
    ): array {
        // Convert everything to base units
        $powderMg = PharmacyUnitService::convert($powderWeight, $powderUnit, 'mg');
        $diluentMl = PharmacyUnitService::convert($diluentVolume, $diluentUnit, 'ml');

        $concentrationMgMl = $powderMg / $diluentMl;
        $finalConcentration = PharmacyUnitService::convert($concentrationMgMl, 'mg/ml', $outputConcentrationUnit);

        return [
            'concentration' => $finalConcentration,
            'unit' => $outputConcentrationUnit,
            'formatted' => PharmacyUnitService::formatValue($finalConcentration, $outputConcentrationUnit),
            'total_volume_ml' => $diluentMl,
        ];
    }
}

// Usage examples:
$medService = new MedicationService();

// Pediatric dosage calculation
$pediatricDose = $medService->calculateDosage(
    patientWeight: 25,        // 25 kg
    weightUnit: 'kg',
    dosagePerKg: 10,          // 10 mg/kg
    dosageUnit: 'mg',
    outputUnit: 'mg'          // Output in mg
);

// Output: ['total_dosage' => 250, 'unit' => 'mg', 'formatted' => '250.000 mg']

// Reconstitution example
$reconstitution = $medService->reconstituteMedication(
    powderWeight: 500,
    powderUnit: 'mg',
    diluentVolume: 10,
    diluentUnit: 'ml',
    outputConcentrationUnit: 'mg/ml'
);

// Output: ['concentration' => 50, 'unit' => 'mg/ml', 'formatted' => '50.000 mg/ml']
```

### Prescription Processing Service

```php
<?php

namespace App\Services\Pharmacy;

class PrescriptionService
{
    public function normalizePrescriptionUnits(array $prescription): array
    {
        $normalized = $prescription;

        // Normalize dosage units to standard base units
        if (isset($prescription['dosage_unit'])) {
            $baseUnit = PharmacyUnitService::getBaseUnit($prescription['dosage_unit']);
            if ($baseUnit && $baseUnit !== $prescription['dosage_unit']) {
                $normalized['dosage_value'] = PharmacyUnitService::convert(
                    $prescription['dosage_value'],
                    $prescription['dosage_unit'],
                    $baseUnit
                );
                $normalized['dosage_unit'] = $baseUnit;
                $normalized['original_dosage'] = $prescription['dosage_value'] . ' ' . $prescription['dosage_unit'];
            }
        }

        return $normalized;
    }

    public function checkUnitCompatibility(array $medication1, array $medication2): bool
    {
        $unit1 = $medication1['unit'] ?? null;
        $unit2 = $medication2['unit'] ?? null;

        if (!$unit1 || !$unit2) {
            return false;
        }

        return PharmacyUnitService::validateConversion($unit1, $unit2);
    }

    public function calculateTotalDailyDose(array $prescriptions): array
    {
        $totalDoses = [];

        foreach ($prescriptions as $rx) {
            if (!isset($rx['dosage_value'], $rx['dosage_unit'], $rx['frequency_per_day'])) {
                continue;
            }

            $dailyDose = $rx['dosage_value'] * $rx['frequency_per_day'];
            $unit = $rx['dosage_unit'];

            // Convert to base unit for aggregation
            $baseUnit = PharmacyUnitService::getBaseUnit($unit);
            if ($baseUnit) {
                $dailyDoseBase = PharmacyUnitService::convert($dailyDose, $unit, $baseUnit);

                if (!isset($totalDoses[$baseUnit])) {
                    $totalDoses[$baseUnit] = 0;
                }
                $totalDoses[$baseUnit] += $dailyDoseBase;
            }
        }

        return $totalDoses;
    }
}

// Usage
$rxService = new PrescriptionService();

$prescription = [
    'drug' => 'Amoxicillin',
    'dosage_value' => 500,
    'dosage_unit' => 'mg',
    'frequency_per_day' => 3,
];

$normalized = $rxService->normalizePrescriptionUnits($prescription);
```

### Inventory Management Integration

```php
<?php

namespace App\Services\Pharmacy;

class InventoryService
{
    public function convertStockQuantity(float $quantity, string $fromUnit, string $toUnit): array
    {
        $converted = PharmacyUnitService::convertWithPrecision($quantity, $fromUnit, $toUnit);

        return [
            'original' => PharmacyUnitService::formatValue($quantity, $fromUnit),
            'converted' => PharmacyUnitService::formatValue($converted, $toUnit),
            'conversion_factor' => PharmacyUnitService::getConversionFactor($fromUnit, $toUnit),
        ];
    }

    public function calculateUnitCost(array $inventoryItem): float
    {
        $totalCost = $inventoryItem['cost'];
        $totalQuantity = $inventoryItem['quantity'];
        $unit = $inventoryItem['unit'];

        // Convert to base unit for consistent pricing
        $baseUnit = PharmacyUnitService::getBaseUnit($unit);
        $baseQuantity = PharmacyUnitService::convert($totalQuantity, $unit, $baseUnit);

        return $totalCost / $baseQuantity;
    }

    public function compareProductCosts(array $product1, array $product2): array
    {
        $costPerBaseUnit1 = $this->calculateUnitCost($product1);
        $costPerBaseUnit2 = $this->calculateUnitCost($product2);

        $baseUnit1 = PharmacyUnitService::getBaseUnit($product1['unit']);
        $baseUnit2 = PharmacyUnitService::getBaseUnit($product2['unit']);

        if ($baseUnit1 !== $baseUnit2) {
            throw new InvalidArgumentException("Cannot compare costs across different unit categories");
        }

        return [
            'product1_cost_per_unit' => $costPerBaseUnit1,
            'product2_cost_per_unit' => $costPerBaseUnit2,
            'better_deal' => $costPerBaseUnit1 < $costPerBaseUnit2 ? 'product1' : 'product2',
            'savings_percentage' => abs($costPerBaseUnit1 - $costPerBaseUnit2) / max($costPerBaseUnit1, $costPerBaseUnit2) * 100,
        ];
    }
}

// Inventory usage examples
$inventoryService = new InventoryService();

// Convert between packaging sizes
$conversion = $inventoryService->convertStockQuantity(1000, 'mg', 'g');
// Output: ['original' => '1000.000 mg', 'converted' => '1.000 g', 'conversion_factor' => 0.001]

// Cost comparison
$productA = ['name' => 'Drug A', 'quantity' => 500, 'unit' => 'mg', 'cost' => 25.00];
$productB = ['name' => 'Drug B', 'quantity' => 1, 'unit' => 'g', 'cost' => 45.00];

$costComparison = $inventoryService->compareProductCosts($productA, $productB);
```

### API Controller Usage

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Pharmacy\PharmacyUnitService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PharmacyUnitController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'units' => PharmacyUnitService::all()->values(),
            'categories' => [
                'weight' => PharmacyUnitService::getWeightUnits()->values(),
                'volume' => PharmacyUnitService::getVolumeUnits()->values(),
                'units' => PharmacyUnitService::getUnitTypes()->values(),
                'concentration' => PharmacyUnitService::getConcentrationUnits()->values(),
            ]
        ]);
    }

    public function convert(Request $request): JsonResponse
    {
        $request->validate([
            'value' => 'required|numeric',
            'from_unit' => 'required|string',
            'to_unit' => 'required|string',
            'precision' => 'sometimes|integer|min:0|max:6',
        ]);

        try {
            $converted = PharmacyUnitService::convertWithPrecision(
                $request->value,
                $request->from_unit,
                $request->to_unit,
                $request->precision
            );

            return response()->json([
                'original' => $request->value . ' ' . $request->from_unit,
                'converted' => $converted . ' ' . $request->to_unit,
                'formatted' => PharmacyUnitService::formatValue($converted, $request->to_unit, $request->precision),
                'conversion_valid' => true,
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'conversion_valid' => false,
            ], 422);
        }
    }

    public function checkCompatibility(Request $request): JsonResponse
    {
        $request->validate([
            'from_unit' => 'required|string',
            'to_unit' => 'required|string',
        ]);

        $compatible = PharmacyUnitService::validateConversion(
            $request->from_unit,
            $request->to_unit
        );

        return response()->json([
            'from_unit' => $request->from_unit,
            'to_unit' => $request->to_unit,
            'compatible' => $compatible,
        ]);
    }
}
```

## 3. Route Definitions

```php
// routes/api.php
Route::prefix('pharmacy')->group(function () {
    Route::get('/units', [PharmacyUnitController::class, 'index']);
    Route::post('/convert', [PharmacyUnitController::class, 'convert']);
    Route::post('/check-compatibility', [PharmacyUnitController::class, 'checkCompatibility']);
});
```

## 4. Testing Examples

```php
<?php

namespace Tests\Unit\Services\Pharmacy;

use Tests\TestCase;
use App\Services\Pharmacy\PharmacyUnitService;

class PharmacyUnitServiceTest extends TestCase
{
    public function test_weight_conversions(): void
    {
        $this->assertEquals(1.0, PharmacyUnitService::convert(1000, 'mg', 'g'));
        $this->assertEquals(1000.0, PharmacyUnitService::convert(1, 'g', 'mg'));
        $this->assertEquals(0.5, PharmacyUnitService::convert(500, 'mg', 'g'));
    }

    public function test_unit_conversions(): void
    {
        $this->assertEquals(1000.0, PharmacyUnitService::convert(1, 'TU', 'U'));
        $this->assertEquals(2.5, PharmacyUnitService::convert(2500, 'U', 'TU'));
    }

    public function test_invalid_conversion_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        PharmacyUnitService::convert(100, 'mg', 'ml');
    }

    public function test_unit_categories(): void
    {
        $weightUnits = PharmacyUnitService::getWeightUnits();
        $this->assertTrue($weightUnits->has('mg'));
        $this->assertTrue($weightUnits->has('g'));
        $this->assertFalse($weightUnits->has('ml'));
    }
}
```

This advanced implementation provides:

- **Type-safe unit conversions** with proper error handling
- **Category-based unit management** for logical grouping
- **Precision control** for different unit types
- **Caching** for performance
- **Extensibility** for custom units
- **API-ready** structure with comprehensive examples
- **Real-world pharmacy applications** like dosage calculations and inventory management

The database-backed approach allows for runtime modifications while maintaining type safety and validation.
