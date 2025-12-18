<?php
// Modules/Pipeline/Models/PipelineStage.php

namespace Modules\Pipeline\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Pipeline\Services\TypeConfigService;

class PipelineStage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pipeline_id',
        'name',
        'description',
        'color',
        'order',
        'probability',
        'is_default',
        'meta',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'probability' => 'decimal:2',
        'meta' => 'array',
    ];

    protected static function booted()
    {
        static::saved(function ($stage) {
            $stage->clearCache();
        });

        static::deleted(function ($stage) {
            $stage->clearCache();
        });
    }

    public function pipeline()
    {
        $cacheKey = 'pipeline_stage:' . $this->id . ':pipeline';

        return Cache::remember($cacheKey, 3600, function () {
            return $this->belongsTo(Pipeline::class);
        });
    }

    public function getEntities($typeName)
    {
        $cacheKey = 'pipeline_stage:' . $this->id . ':entities:' . $typeName;

        return Cache::remember($cacheKey, 300, function () use ($typeName) {
            $typeService = app(TypeConfigService::class);
            $typeConfig = $typeService->getType($typeName);

            if (!$typeConfig) {
                return collect();
            }

            return DB::table($typeConfig['table_name'])
                ->where('pipeline_id', $this->pipeline_id)
                ->where('pipeline_stage_id', $this->id)
                ->get()
                ->map(function ($item) use ($typeConfig) {
                    $modelClass = $typeConfig['model_class'];
                    if ($modelClass && class_exists($modelClass)) {
                        return $modelClass::find($item->{$typeConfig['foreign_key']});
                    }
                    return $item;
                });
        });
    }

    public function getEntityCount($typeName)
    {
        $cacheKey = 'pipeline_stage:' . $this->id . ':entity_count:' . $typeName;

        return Cache::remember($cacheKey, 300, function () use ($typeName) {
            $typeService = app(TypeConfigService::class);
            $typeConfig = $typeService->getType($typeName);

            if (!$typeConfig) {
                return 0;
            }

            return DB::table($typeConfig['table_name'])
                ->where('pipeline_id', $this->pipeline_id)
                ->where('pipeline_stage_id', $this->id)
                ->count();
        });
    }

    public function getNextStage()
    {
        $cacheKey = 'pipeline_stage:' . $this->id . ':next';

        return Cache::remember($cacheKey, 3600, function () {
            return $this->pipeline->stages()
                ->where('order', '>', $this->order)
                ->orderBy('order')
                ->first();
        });
    }

    public function getPreviousStage()
    {
        $cacheKey = 'pipeline_stage:' . $this->id . ':previous';

        return Cache::remember($cacheKey, 3600, function () {
            return $this->pipeline->stages()
                ->where('order', '<', $this->order)
                ->orderBy('order', 'desc')
                ->first();
        });
    }

    public function clearCache()
    {
        // Clear stage-specific cache
        $keys = [
            'pipeline_stage:' . $this->id . ':pipeline',
            'pipeline_stage:' . $this->id . ':next',
            'pipeline_stage:' . $this->id . ':previous',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        // Clear entity cache for this stage
        // $pattern = 'pipeline_stage:' . $this->id . ':entities:*';
        // $pattern2 = 'pipeline_stage:' . $this->id . ':entity_count:*';

        //Facts (important):

        // Cache::store() returns a Cache Repository

        // flush() is not part of Laravel’s cache repository contract

        // With file / database cache drivers (your setup), pattern deletion is not supported

        // IDE + runtime are correct to complain

        // 👉 Even with the method_exists() guard, this is architecturally wrong and unsafe.

        // if (method_exists(Cache::store(), 'flush')) {
        //     Cache::store()->flush();
        // }

        // Clear entity cache for all registered types
        $typeService = app(TypeConfigService::class);
        $allTypes = $typeService->getTypes(); // This returns all registered types

        foreach ($allTypes as $typeName => $config) {
            Cache::forget('pipeline_stage:' . $this->id . ':entities:' . $typeName);
            Cache::forget('pipeline_stage:' . $this->id . ':entity_count:' . $typeName);
        }

        // Clear parent pipeline cache
        if ($this->pipeline) {
            $this->pipeline->clearCache();
        }
    }
}
