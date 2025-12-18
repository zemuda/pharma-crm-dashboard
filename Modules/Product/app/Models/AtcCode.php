<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Enums\ActiveStatus;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class AtcCode extends Model
{
    use HasFactory, HasRecursiveRelationships, HasSlug, SoftDeletes;

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
        '_lft',
        '_rgt',
        'who_guidelines',
        'therapeutic_uses',
        'contraindications',
        'meta',
        'sort_order',
    ];

    protected $casts = [
        'status' => ActiveStatus::class,
        'meta' => 'array',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(fn($model) => $model->code . '-' . $model->name)
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
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

    /**
     * Scope for active ATC codes
     */
    public function scopeActive($query)
    {
        return $query->where('status', ActiveStatus::Active);
    }

    /**
     * Scope for specific level
     */
    public function scopeLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Get full ATC code path
     */
    public function getFullCodeAttribute(): string
    {
        $ancestors = $this->ancestors()->pluck('code')->toArray();
        $ancestors[] = $this->code;
        return implode(' > ', $ancestors);
    }

    /**
     * Get full ATC name path
     */
    public function getFullNameAttribute(): string
    {
        $ancestors = $this->ancestors()->pluck('name')->toArray();
        $ancestors[] = $this->name;
        return implode(' > ', $ancestors);
    }
}
