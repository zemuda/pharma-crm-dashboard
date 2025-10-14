<?php

namespace Modules\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Enums\ActiveStatus;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

// use Modules\Products\Database\Factories\MedicineFactory;

class Medicine extends Model
{
    use HasFactory, HasRecursiveRelationships, HasSlug;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'generic_name',
        'slug',
        'info',
        'strength_id',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => ActiveStatus::class
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function formulations(): HasMany
    {
        return $this->hasMany(Formulation::class);
    }

    public function theraputicCategories(): BelongsToMany
    {
        return $this->belongsToMany(
            TheraputicCategory::class,
            'medicine_theraputic_categories',
            'medicine_id',
            'theraputic_category_id'
        );
    }

    public function atcCodes(): BelongsToMany
    {
        return $this->belongsToMany(
            AtcCode::class,
            'medicine_atc_codes',
            'medicine_id',
            'atc_code_id'
        );
    }

    // protected static function newFactory(): MedicineFactory
    // {
    //     // return MedicineFactory::new();
    // }
}
