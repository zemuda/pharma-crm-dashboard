<?php

namespace Modules\Deal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

// use Modules\Deal\Database\Factories\PipelineFactory;

class Pipeline extends Model
{
    use HasFactory;

    /**
     * The flag that indicates it's the primary pipeline
     */
    const PRIMARY_FLAG = 'default';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * A pipeline has many deals
     */
    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }

    /**
     * A pipeline has many stages
     */
    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class);
    }

    /**
     * Check whether the pipeline is the primary one
     */
    public function isPrimary(): bool
    {
        return $this->flag === static::PRIMARY_FLAG;
    }

    /**
     * Find the primary pipeline.
     */
    public static function findPrimary(): Pipeline
    {
        return static::where('flag', static::PRIMARY_FLAG)->first();
    }

    // protected static function newFactory(): PipelineFactory
    // {
    //     // return PipelineFactory::new();
    // }
}
