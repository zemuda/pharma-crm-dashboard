<?php

namespace Modules\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Enums\ActiveStatus;
// use Modules\Products\Database\Factories\AtcCodeFactory;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class AtcCode extends Model
{
    use HasFactory, HasRecursiveRelationships, HasSlug;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'parent_id',
        'name',
        'code',
        'level',
        'slug',
        'info',
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

    public function medicines()
    {
        return $this->belongsToMany(
            Medicine::class,
            'medicine_atc_codes',
            'atc_code_id',
            'medicine_id'
        );
    }

    // protected static function newFactory(): AtcCodeFactory
    // {
    //     // return AtcCodeFactory::new();
    // }
}
