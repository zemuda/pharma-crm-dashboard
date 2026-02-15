<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Enums\ActiveStatus;

// use Modules\Products\Database\Factories\RouteOfAdministrationFactory;

class RouteOfAdministration extends Model
{
    use HasFactory;

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

    // public function strengths(): HasMany
    // {
    //     return $this->hasMany(Strength::class);
    // }

    // protected static function newFactory(): RouteOfAdministrationFactory
    // {
    //     // return RouteOfAdministrationFactory::new();
    // }
}
