<?php

namespace Modules\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Core\Enums\ActiveStatus;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

// use Modules\Products\Database\Factories\TheraputicCategoryFactory;

class TheraputicCategory extends Model
{
    use HasFactory, HasRecursiveRelationships, HasSlug;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
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

    public function medicines(): BelongsToMany
    {
        return $this->belongsToMany(
            Medicine::class,
            'medicine_theraputic_categories',
            'theraputic_category_id',
            'medicine_id'
        );
    }

    // protected static function newFactory(): TheraputicCategoryFactory
    // {
    //     // return TheraputicCategoryFactory::new();
    // }
}
