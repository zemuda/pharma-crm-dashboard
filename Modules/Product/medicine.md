Here are several improvements to the formulations schema for better pharmaceutical data management:

## 1. Enhanced Schema with Better Structure

```php
Schema::create('formulations', function (Blueprint $table) {
    $table->id();

    // Core relationships
    $table->foreignId('medicine_id')->constrained('medicines')->onDelete('cascade');
    $table->foreignId('dose_form_id')->constrained('dose_forms')->onDelete('restrict');
    $table->foreignId('route_of_administration_id')->constrained('route_of_administrations')->onDelete('restrict');

    // Usage and classification
    $table->unsignedTinyInteger('level_of_use')->nullable()->comment('1: Pediatric, 2: Adult, 3: Geriatric, 4: All');
    $table->string('formulation_code')->unique()->nullable()->comment('Internal code like AMOX-250-SYR');
    $table->string('brand_name')->nullable()->comment('Commercial brand name if different');
    $table->string('manufacturer')->nullable();

    // Enhanced concentration structure
    $table->decimal('active_ingredient_amount', 12, 6)->comment('Amount of active pharmaceutical ingredient');
    $table->string('active_ingredient_unit', 10);
    $table->decimal('total_amount', 12, 6)->nullable()->comment('Total amount including salts, solvents etc.');
    $table->string('total_amount_unit', 10)->nullable();

    // For liquid formulations
    $table->decimal('volume_per_unit', 12, 6)->nullable()->comment('Volume per single unit (tablet, capsule, etc.)');
    $table->string('volume_unit', 10)->nullable();
    $table->decimal('total_volume', 12, 6)->nullable()->comment('Total volume in container');
    $table->string('total_volume_unit', 10)->nullable();

    // Chemical properties
    $table->string('salt_form')->nullable()->comment('e.g., hydrochloride, sulphate');
    $table->decimal('salt_factor', 8, 4)->nullable()->comment('Conversion factor to base molecule');
    $table->decimal('molecular_weight', 10, 4)->nullable()->comment('For molar calculations');
    $table->decimal('equivalent_amount', 12, 6)->nullable()->comment('Equivalent base molecule amount');
    $table->string('equivalent_unit', 10)->nullable();

    // Packaging and administration
    $table->integer('units_per_container')->nullable()->comment('Tablets per bottle, vials per box');
    $table->string('container_type')->nullable()->comment('Bottle, vial, blister pack, etc.');
    $table->decimal('single_dose_volume', 8, 3)->nullable()->comment('For pre-filled syringes, single-use vials');
    $table->string('single_dose_unit', 10)->nullable();

    // Calculated fields for performance
    $table->decimal('base_strength', 15, 9)->nullable()->comment('Strength in base units for comparisons');
    $table->string('base_strength_unit', 10)->nullable();
    $table->decimal('concentration_mg_ml', 12, 6)->nullable()->comment('Standardized concentration for queries');

    // Regulatory and safety
    $table->boolean('is_controlled')->default(false)->comment('Controlled substance');
    $table->boolean('requires_refrigeration')->default(false);
    $table->integer('shelf_life_months')->nullable();
    $table->string('storage_conditions')->nullable()->comment('Room temp, refrigerated, protected from light');

    // Additional information
    $table->json('excipients')->nullable()->comment('List of inactive ingredients');
    $table->string('flavor')->nullable();
    $table->string('color')->nullable();
    $table->text('reconstitution_instructions')->nullable();
    $table->text('administration_instructions')->nullable();

    // Status and metadata
    $table->string('status')->default(ActiveStatus::Active->value);
    $table->text('description')->nullable();
    $table->text('warning')->nullable()->comment('Special warnings or precautions');
    $table->timestamp('discontinued_at')->nullable();

    // Audit fields
    $table->foreignId('created_by')->nullable()->constrained('users');
    $table->foreignId('updated_by')->nullable()->constrained('users');
    $table->timestamps();

    // Indexes for performance
    $table->index(['medicine_id', 'status']);
    $table->index(['dose_form_id', 'level_of_use']);
    $table->index(['base_strength', 'base_strength_unit']);
    $table->index(['formulation_code']);
    $table->index(['brand_name']);
    $table->index(['is_controlled', 'status']);
    $table->index(['requires_refrigeration', 'status']);

    // Full-text search
    $table->fullText(['brand_name', 'description', 'administration_instructions']);
});
```

## 2. Supporting Tables for Enhanced Data Integrity

```php
// Dose forms with additional metadata
Schema::create('dose_forms', function (Blueprint $table) {
    $table->id();
    $table->string('name'); // e.g., Tablet, Syrup, Injection
    $table->string('code')->unique(); // e.g., TAB, SYR, INJ
    $table->string('category'); // Solid, Liquid, Semi-solid, Parenteral
    $table->boolean('is_liquid')->default(false);
    $table->boolean('requires_reconstitution')->default(false);
    $table->boolean('is_sterile')->default(false);
    $table->text('description')->nullable();
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});

// Route of administration with additional details
Schema::create('route_of_administrations', function (Blueprint $table) {
    $table->id();
    $table->string('name'); // e.g., Oral, Intravenous, Topical
    $table->string('code')->unique(); // e.g., PO, IV, TOP
    $table->string('category'); // Enteral, Parenteral, Topical
    $table->boolean('is_invasive')->default(false);
    $table->text('description')->nullable();
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});

// Excipients table for better ingredient tracking
Schema::create('excipients', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('category')->nullable(); // Binder, preservative, flavoring, etc.
    $table->text('function')->nullable();
    $table->boolean('is_allergen')->default(false);
    $table->string('common_allergens')->nullable(); // gluten, lactose, etc.
    $table->timestamps();
});

// Formulation-excipient relationship
Schema::create('formulation_excipient', function (Blueprint $table) {
    $table->id();
    $table->foreignId('formulation_id')->constrained()->onDelete('cascade');
    $table->foreignId('excipient_id')->constrained()->onDelete('cascade');
    $table->decimal('amount', 10, 4)->nullable();
    $table->string('unit', 10)->nullable();
    $table->string('purpose')->nullable();
    $table->timestamps();

    $table->unique(['formulation_id', 'excipient_id']);
});
```

## 3. Enhanced Model with Business Logic

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\Pharmacy\PharmacyUnitService;

class Formulation extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id',
        'dose_form_id',
        'route_of_administration_id',
        'level_of_use',
        'formulation_code',
        'brand_name',
        'manufacturer',
        'active_ingredient_amount',
        'active_ingredient_unit',
        'total_amount',
        'total_amount_unit',
        'volume_per_unit',
        'volume_unit',
        'total_volume',
        'total_volume_unit',
        'salt_form',
        'salt_factor',
        'molecular_weight',
        'equivalent_amount',
        'equivalent_unit',
        'units_per_container',
        'container_type',
        'single_dose_volume',
        'single_dose_unit',
        'base_strength',
        'base_strength_unit',
        'concentration_mg_ml',
        'is_controlled',
        'requires_refrigeration',
        'shelf_life_months',
        'storage_conditions',
        'excipients',
        'flavor',
        'color',
        'reconstitution_instructions',
        'administration_instructions',
        'status',
        'description',
        'warning',
        'discontinued_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'active_ingredient_amount' => 'decimal:6',
        'total_amount' => 'decimal:6',
        'volume_per_unit' => 'decimal:6',
        'total_volume' => 'decimal:6',
        'salt_factor' => 'decimal:4',
        'molecular_weight' => 'decimal:4',
        'equivalent_amount' => 'decimal:6',
        'single_dose_volume' => 'decimal:3',
        'base_strength' => 'decimal:9',
        'concentration_mg_ml' => 'decimal:6',
        'is_controlled' => 'boolean',
        'requires_refrigeration' => 'boolean',
        'excipients' => 'array',
        'discontinued_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($formulation) {
            $formulation->calculateBaseUnits();
            $formulation->generateFormulationCode();
            $formulation->calculateEquivalentAmount();
            $formulation->calculateConcentration();
        });
    }

    public function calculateBaseUnits(): void
    {
        // Calculate base strength for easy comparison
        if ($this->active_ingredient_amount && $this->active_ingredient_unit) {
            $baseUnit = PharmacyUnitService::getBaseUnit($this->active_ingredient_unit);
            if ($baseUnit) {
                $this->base_strength = PharmacyUnitService::convert(
                    $this->active_ingredient_amount,
                    $this->active_ingredient_unit,
                    $baseUnit
                );
                $this->base_strength_unit = $baseUnit;
            }
        }
    }

    public function calculateEquivalentAmount(): void
    {
        // Calculate equivalent base molecule amount considering salt form
        if ($this->active_ingredient_amount && $this->salt_factor) {
            $this->equivalent_amount = $this->active_ingredient_amount * $this->salt_factor;
            $this->equivalent_unit = $this->active_ingredient_unit;
        }
    }

    public function calculateConcentration(): void
    {
        // Calculate standardized concentration in mg/ml for liquids
        if ($this->doseForm && $this->doseForm->is_liquid &&
            $this->active_ingredient_amount && $this->volume_per_unit) {

            $ingredientMg = PharmacyUnitService::convert(
                $this->active_ingredient_amount,
                $this->active_ingredient_unit,
                'mg'
            );

            $volumeMl = PharmacyUnitService::convert(
                $this->volume_per_unit,
                $this->volume_unit,
                'ml'
            );

            if ($volumeMl > 0) {
                $this->concentration_mg_ml = $ingredientMg / $volumeMl;
            }
        }
    }

    public function generateFormulationCode(): void
    {
        if (!$this->formulation_code && $this->medicine && $this->doseForm) {
            $medicineCode = strtoupper(substr($this->medicine->name, 0, 4));
            $strength = $this->active_ingredient_amount;
            $unit = $this->active_ingredient_unit;
            $formCode = $this->doseForm->code;

            $this->formulation_code = "{$medicineCode}-{$strength}{$unit}-{$formCode}";
        }
    }

    // Relationships
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function doseForm()
    {
        return $this->belongsTo(DoseForm::class);
    }

    public function routeOfAdministration()
    {
        return $this->belongsTo(RouteOfAdministration::class);
    }

    public function excipients()
    {
        return $this->belongsToMany(Excipient::class)
            ->withPivot('amount', 'unit', 'purpose')
            ->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', ActiveStatus::Active->value)
            ->whereNull('discontinued_at');
    }

    public function scopeForRoute($query, $routeId)
    {
        return $query->where('route_of_administration_id', $routeId);
    }

    public function scopeLiquidForms($query)
    {
        return $query->whereHas('doseForm', function ($q) {
            $q->where('is_liquid', true);
        });
    }

    public function scopeWithStrengthRange($query, $min, $max, $unit)
    {
        $baseMin = PharmacyUnitService::convert($min, $unit, 'mg');
        $baseMax = PharmacyUnitService::convert($max, $unit, 'mg');

        return $query->whereBetween('base_strength', [$baseMin, $baseMax]);
    }

    public function scopeControlledSubstances($query)
    {
        return $query->where('is_controlled', true);
    }

    public function scopeRefrigerated($query)
    {
        return $query->where('requires_refrigeration', true);
    }

    // Accessors
    public function getFormattedStrengthAttribute(): string
    {
        return PharmacyUnitService::formatValue(
            $this->active_ingredient_amount,
            $this->active_ingredient_unit
        );
    }

    public function getFormattedVolumeAttribute(): ?string
    {
        if (!$this->volume_per_unit || !$this->volume_unit) {
            return null;
        }

        return PharmacyUnitService::formatValue(
            $this->volume_per_unit,
            $this->volume_unit
        );
    }

    public function getIsDiscontinuedAttribute(): bool
    {
        return !is_null($this->discontinued_at);
    }

    public function getTherapeuticEquivalentsAttribute()
    {
        return self::where('medicine_id', $this->medicine_id)
            ->where('id', '!=', $this->id)
            ->active()
            ->get();
    }
}
```

## 4. Key Improvements Summary

**1. Enhanced Data Structure:**

- Separate active ingredient vs total amount
- Better volume tracking (per unit + total container)
- Salt form with conversion factors

**2. Pharmaceutical Accuracy:**

- Molecular weight for molar calculations
- Equivalent base amount calculations
- Standardized concentration fields

**3. Practical Clinical Use:**

- Single-dose volumes for injections
- Container types and units per package
- Administration instructions

**4. Safety and Compliance:**

- Controlled substance flag
- Storage requirements
- Allergen tracking via excipients

**5. Performance Optimization:**

- Calculated fields for faster queries
- Comprehensive indexing
- Full-text search capabilities

**6. Business Logic:**

- Automatic code generation
- Strength comparisons
- Therapeutic equivalence

This enhanced schema supports complex pharmaceutical scenarios while maintaining data integrity and performance.
