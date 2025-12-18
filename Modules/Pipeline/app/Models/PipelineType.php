<?php
// Modules/Pipeline/Models/PipelineType.php

namespace Modules\Pipeline\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PipelineType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'display_name',
        'model_class',
        'table_name',
        'foreign_key',
        'route_key',
        'is_active',
        'has_probability',
        'has_value',
        'has_due_date',
        'has_priority',
        'custom_fields',
        'validations',
        'default_stages',
        'order',
        'icon',
        'color',
        'meta',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_probability' => 'boolean',
        'has_value' => 'boolean',
        'has_due_date' => 'boolean',
        'has_priority' => 'boolean',
        'custom_fields' => 'array',
        'validations' => 'array',
        'default_stages' => 'array',
        'meta' => 'array',
    ];

    protected static function booted()
    {
        static::saved(function ($pipelineType) {
            // Clear cache when pipeline type is saved
            $pipelineType->clearCache();
        });

        static::deleted(function ($pipelineType) {
            // Clear cache when pipeline type is deleted
            $pipelineType->clearCache();
        });
    }

    public function pipelines()
    {
        return $this->belongsToMany(Pipeline::class, 'pipeline_type_associations')
            ->withPivot(['is_default', 'settings'])
            ->withTimestamps();
    }

    public function getDefaultPipeline()
    {
        $cacheKey = 'pipeline_type:' . $this->id . ':default_pipeline';

        return Cache::remember($cacheKey, 3600, function () {
            return $this->pipelines()
                ->wherePivot('is_default', true)
                ->first();
        });
    }

    public function getModelInstance()
    {
        $cacheKey = 'pipeline_type:' . $this->id . ':model_instance';

        return Cache::remember($cacheKey, 3600, function () {
            // 1. Check if the explicit model class exists (highest priority)
            if ($this->model_class && class_exists($this->model_class)) {
                return app($this->model_class);
            }

            // Convert 'deals' to 'Deal' (singular, capitalized)
            $singularName = Str::singular(ucfirst($this->name));

            // 2. Try multiple naming conventions for modular models
            $possibleModuleClasses = [
                // Modules\Deal\Models\Deal
                'Modules\\' . $singularName . '\\Models\\' . $singularName,
                // Modules\Deal\Entities\Deal
                'Modules\\' . $singularName . '\\Entities\\' . $singularName,
                // Modules\Deals\Models\Deal (if module name is plural)
                'Modules\\' . Str::plural($singularName) . '\\Models\\' . $singularName,
                // Modules\Deals\Entities\Deal
                'Modules\\' . Str::plural($singularName) . '\\Entities\\' . $singularName,
            ];

            foreach ($possibleModuleClasses as $moduleClass) {
                if (class_exists($moduleClass)) {
                    return app($moduleClass);
                }
            }

            // 3. Try App namespace with different conventions
            $possibleAppClasses = [
                // App\Models\Deal
                'App\\Models\\' . $singularName,
                // App\Entities\Deal
                'App\\Entities\\' . $singularName,
                // App\Deal (if model is directly in App namespace)
                'App\\' . $singularName,
            ];

            foreach ($possibleAppClasses as $appClass) {
                if (class_exists($appClass)) {
                    return app($appClass);
                }
            }

            return null;
        });
    }

    public function getModelQuery()
    {
        $instance = $this->getModelInstance();

        if (!$instance) {
            // Return a dummy query builder that won't execute
            return DB::table('dummy')->whereRaw('1 = 0');
        }

        return $instance->newQuery();
    }

    public function findEntity($id)
    {
        $cacheKey = 'pipeline_type:' . $this->id . ':entity:' . $id;

        return Cache::remember($cacheKey, 300, function () use ($id) {
            $instance = $this->getModelInstance();

            if (!$instance) {
                return null;
            }

            return $instance->find($id);
        });
    }

    public function clearCache()
    {
        $cacheKeys = [
            'pipeline_type:' . $this->id . ':default_pipeline',
            'pipeline_type:' . $this->id . ':model_instance',
            'pipeline:type:config:' . $this->name,
            'pipeline:type:' . $this->name,
            'pipeline:type:pipelines:' . $this->name,
            'pipeline:type:default:' . $this->name,
            'pipeline:type:default-stages:' . $this->name,
            'pipeline:type:validations:' . $this->name,
            'pipeline:type:custom-fields:' . $this->name,
            'pipeline:types',
            'pipeline:types:active',
            'pipeline:types:db',
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        // --- FIX APPLIED HERE ---
        // The following block is removed because:
        // 1. File/Database drivers do not support 'flush()' or pattern matching ('*').
        // 2. Calling flush() would clear the entire application cache.
        // 3. This could lead to unintended data loss and performance issues.
        // 
        // // original comment: Clear entity cache
        // Original problematic code:
        // $pattern = 'pipeline_type:' . $this->id . ':entity:*';
        // if (method_exists(Cache::store(), 'flush')) {
        //     Cache::store()->flush();
        // }
    }

    // Getters with caching
    public function getTableNameAttribute($value)
    {
        return $value ?? $this->name . '_pipeline';
    }

    public function getForeignKeyAttribute($value)
    {
        return $value ?? $this->name . '_id';
    }

    public function getRouteKeyAttribute($value)
    {
        return $value ?? $this->name . 's';
    }

    public function getCachedAttributes()
    {
        $cacheKey = 'pipeline_type:' . $this->id . ':attributes';

        return Cache::remember($cacheKey, 3600, function () {
            return [
                'table_name' => $this->table_name,
                'foreign_key' => $this->foreign_key,
                'route_key' => $this->route_key,
                'has_probability' => $this->has_probability,
                'has_value' => $this->has_value,
                'has_due_date' => $this->has_due_date,
                'has_priority' => $this->has_priority,
                'custom_fields' => $this->custom_fields,
                'validations' => $this->validations,
            ];
        });
    }
}
