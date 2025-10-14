<?php

namespace Modules\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Enums\ActiveStatus;
use Modules\Core\Enums\LevelOfUse;

// use Modules\Products\Database\Factories\FormulationFactory;

class Formulation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'dose_form_id',
        'route_of_administration_id',
        'level_of_use',
        'concentration_amount',
        'concentration_unit',
        'volume_amount',
        'volume_unit',
        'chemical_form',
        'info',
        'description',
        'status'
    ];

    protected $casts = [
        'level_of_use' => LevelOfUse::class,
        'status' => ActiveStatus::class
    ];

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function doseForm(): BelongsTo
    {
        return $this->belongsTo(DoseForm::class);
    }

    public function routeOfAdministration(): BelongsTo
    {
        return $this->belongsTo(RouteOfAdministration::class);
    }

    // protected static function newFactory(): FormulationFactory
    // {
    //     // return FormulationFactory::new();
    // }
}
