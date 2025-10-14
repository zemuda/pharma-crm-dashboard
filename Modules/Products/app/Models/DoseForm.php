<?php

namespace Modules\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Enums\ActiveStatus;
// use Modules\Products\Database\Factories\DoseFormFactory;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class DoseForm extends Model
{
    use HasFactory, HasRecursiveRelationships;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'parent_id',
        'name',
        'code',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => ActiveStatus::class
    ];

    public function formulations(): HasMany
    {
        return $this->hasMany(Formulation::class);
    }

    // protected static function newFactory(): DoseFormFactory
    // {
    //     // return DoseFormFactory::new();
    // }
}
