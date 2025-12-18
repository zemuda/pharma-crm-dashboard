<?php
// Modules/Pipeline/Models/Pipeline.php

namespace Modules\Pipeline\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Modules\Pipeline\Services\TypeConfigService;

class Pipeline extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'is_default',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::saved(function ($pipeline) {
            $pipeline->clearCache();
        });

        static::deleted(function ($pipeline) {
            $pipeline->clearCache();
        });

        static::created(function ($pipeline) {
            // Clear type cache when new pipeline is created
            if ($pipeline->types()->exists()) {
                foreach ($pipeline->types as $type) {
                    app(TypeConfigService::class)->clearPipelineCacheForType($type->name);
                }
            }
        });
    }

    public function stages()
    {
        $cacheKey = 'pipeline:' . $this->id . ':stages';

        return Cache::remember($cacheKey, 3600, function () {
            return $this->hasMany(PipelineStage::class)
                ->orderBy('order')
                ->get();
        });
    }

    public function activeStages()
    {
        $cacheKey = 'pipeline:' . $this->id . ':active_stages';

        return Cache::remember($cacheKey, 3600, function () {
            return $this->stages()->whereNull('deleted_at');
        });
    }

    public function types()
    {
        return $this->belongsToMany(PipelineType::class, 'pipeline_type_associations')
            ->withPivot(['is_default', 'settings'])
            ->withTimestamps();
    }

    public function getCachedStages()
    {
        return $this->stages(); // Already cached
    }

    public function getFirstStage()
    {
        $cacheKey = 'pipeline:' . $this->id . ':first_stage';

        return Cache::remember($cacheKey, 3600, function () {
            return $this->stages()->orderBy('order')->first();
        });
    }

    public function getLastStage()
    {
        $cacheKey = 'pipeline:' . $this->id . ':last_stage';

        return Cache::remember($cacheKey, 3600, function () {
            return $this->stages()->orderBy('order', 'desc')->first();
        });
    }

    public function getStage($stageId)
    {
        $cacheKey = 'pipeline:' . $this->id . ':stage:' . $stageId;

        return Cache::remember($cacheKey, 3600, function () use ($stageId) {
            return $this->stages()->find($stageId);
        });
    }

    public function getStageByOrder($order)
    {
        $cacheKey = 'pipeline:' . $this->id . ':stage_order:' . $order;

        return Cache::remember($cacheKey, 3600, function () use ($order) {
            return $this->stages()->where('order', $order)->first();
        });
    }

    public function clearCache()
    {
        $cacheKeys = [
            'pipeline:' . $this->id,
            'pipeline:' . $this->id . ':stages',
            'pipeline:' . $this->id . ':active_stages',
            'pipeline:' . $this->id . ':first_stage',
            'pipeline:' . $this->id . ':last_stage',
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        // Option 1: Remove flush entirely (BEST for file/database cache)

        // You already explicitly forget all keys you created — that is the correct approach

        // // Clear stage-specific cache
        // $pattern = 'pipeline:' . $this->id . ':stage:*';
        // if (method_exists(Cache::store(), 'flush')) {
        //     Cache::store()->flush();
        // }

        //CLAUDE AI: Clear stage-specific cache for all stages in this pipeline
        // Get the stages to clear their individual cache keys
        /**
         * This solution:
         * Removes the problematic flush() call that doesn't work with file/array cache drivers
         * Explicitly fetches and clears each stage's cache by looping through the pipeline's stages
         * Clears both stage ID and stage order cache keys
         * Works with any cache driver without risking unintended cache clearing
         */
        $stages = $this->hasMany(PipelineStage::class)->get();
        foreach ($stages as $stage) {
            Cache::forget('pipeline:' . $this->id . ':stage:' . $stage->id);
            Cache::forget('pipeline:' . $this->id . ':stage_order:' . $stage->order);
        }

        // Clear cache for associated types
        if ($this->relationLoaded('types')) {
            $service = app(TypeConfigService::class);
            foreach ($this->types as $type) {
                $service->clearPipelineCacheForType($type->name);
            }
        }
    }

    // Cache helper methods
    public static function getCachedPipeline($id)
    {
        $cacheKey = 'pipeline:' . $id;

        return Cache::remember($cacheKey, 3600, function () use ($id) {
            return self::with(['types', 'stages'])->find($id);
        });
    }

    public static function getCachedActivePipelines()
    {
        $cacheKey = 'pipelines:active';

        return Cache::remember($cacheKey, 3600, function () {
            return self::where('is_active', true)
                ->with(['types', 'stages'])
                ->orderBy('order')
                ->get();
        });
    }

    public static function getCachedDefaultPipeline()
    {
        $cacheKey = 'pipelines:default';

        return Cache::remember($cacheKey, 3600, function () {
            return self::where('is_default', true)
                ->with(['types', 'stages'])
                ->first();
        });
    }

    public static function clearAllCache()
    {
        Cache::forget('pipelines:active');
        Cache::forget('pipelines:default');

        // Clear individual pipeline cache
        self::all()->each(function ($pipeline) {
            $pipeline->clearCache();
        });
    }
}
