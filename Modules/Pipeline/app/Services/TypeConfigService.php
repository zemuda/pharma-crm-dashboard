<?php
// Modules/Pipeline/Services/TypeConfigService.php

namespace Modules\Pipeline\Services;

use Modules\Pipeline\Models\PipelineType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TypeConfigService
{
    protected $types;
    protected $registeredTypes = [];

    // Cache keys
    const CACHE_KEY_TYPES = 'pipeline:types';
    const CACHE_KEY_TYPE_PREFIX = 'pipeline:type:';
    const CACHE_KEY_ACTIVE_TYPES = 'pipeline:types:active';
    const CACHE_KEY_PIPELINES_FOR_TYPE_PREFIX = 'pipeline:type:pipelines:';
    const CACHE_DURATION = 3600; // 1 hour

    public function __construct()
    {
        $this->loadTypes();
    }

    public function registerType(array $config): self
    {
        $name = $config['name'];
        $this->registeredTypes[$name] = array_merge([
            'display_name' => Str::title(str_replace('_', ' ', $name)),
            'model_class' => null,
            'table_name' => $name . '_pipeline',
            'foreign_key' => $name . '_id',
            'route_key' => $name . 's',
            'is_active' => true,
            'has_probability' => false,
            'has_value' => false,
            'has_due_date' => false,
            'has_priority' => false,
            'custom_fields' => [],
            'validations' => [],
            'default_stages' => [],
            'order' => 0,
            'icon' => 'heroicon-o-collection',
            'color' => '#3b82f6',
            'meta' => [],
        ], $config);

        return $this;
    }

    public function getType(string $name): ?array
    {
        // Try cache first
        $cacheKey = self::CACHE_KEY_TYPE_PREFIX . $name;
        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($name) {
            return $this->registeredTypes[$name] ?? null;
        });
    }

    public function getTypes(): array
    {
        return Cache::remember(self::CACHE_KEY_TYPES, self::CACHE_DURATION, function () {
            return $this->registeredTypes;
        });
    }

    public function getActiveTypes(): array
    {
        return Cache::remember(self::CACHE_KEY_ACTIVE_TYPES, self::CACHE_DURATION, function () {
            return array_filter($this->registeredTypes, function ($type) {
                return $type['is_active'];
            });
        });
    }

    public function getTypeConfig(string $name): ?PipelineType
    {
        $cacheKey = 'pipeline:type:config:' . $name;

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($name) {
            return PipelineType::where('name', $name)->first();
        });
    }

    public function syncTypes(): void
    {
        foreach ($this->registeredTypes as $name => $config) {
            PipelineType::updateOrCreate(
                ['name' => $name],
                $config
            );
        }

        // Deactivate types that are no longer registered
        PipelineType::whereNotIn('name', array_keys($this->registeredTypes))
            ->update(['is_active' => false]);

        // Clear all type-related cache
        $this->clearCache();
    }

    public function clearCache(): void
    {
        // Clear type cache
        Cache::forget(self::CACHE_KEY_TYPES);
        Cache::forget(self::CACHE_KEY_ACTIVE_TYPES);

        // Clear individual type cache
        foreach (array_keys($this->registeredTypes) as $typeName) {
            Cache::forget(self::CACHE_KEY_TYPE_PREFIX . $typeName);
            Cache::forget('pipeline:type:config:' . $typeName);
            Cache::forget(self::CACHE_KEY_PIPELINES_FOR_TYPE_PREFIX . $typeName);
        }

        // Clear pipeline cache
        Cache::tags(['pipelines'])->flush();
    }

    public function getDefaultStagesForType(string $typeName): array
    {
        $cacheKey = 'pipeline:type:default-stages:' . $typeName;

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($typeName) {
            $type = $this->getType($typeName);

            if ($type && !empty($type['default_stages'])) {
                return $type['default_stages'];
            }

            // Return generic default stages
            return [
                ['name' => 'New', 'color' => '#f59e0b', 'order' => 0],
                ['name' => 'In Progress', 'color' => '#3b82f6', 'order' => 1],
                ['name' => 'Review', 'color' => '#8b5cf6', 'order' => 2],
                ['name' => 'Completed', 'color' => '#10b981', 'order' => 3],
            ];
        });
    }

    public function getTypeValidations(string $typeName): array
    {
        $cacheKey = 'pipeline:type:validations:' . $typeName;

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($typeName) {
            $type = $this->getType($typeName);
            return $type['validations'] ?? [];
        });
    }

    public function getTypeCustomFields(string $typeName): array
    {
        $cacheKey = 'pipeline:type:custom-fields:' . $typeName;

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($typeName) {
            $type = $this->getType($typeName);
            return $type['custom_fields'] ?? [];
        });
    }

    public function createPipelineForType(string $typeName, array $pipelineData): \Modules\Pipeline\Models\Pipeline
    {
        $type = $this->getTypeConfig($typeName);
        if (!$type) {
            throw new \Exception("Type {$typeName} not found");
        }

        $pipeline = \Modules\Pipeline\Models\Pipeline::create(array_merge($pipelineData, [
            'meta' => ['type' => $typeName]
        ]));

        // Add default stages for this type
        $defaultStages = $this->getDefaultStagesForType($typeName);
        foreach ($defaultStages as $stageData) {
            $pipeline->stages()->create($stageData);
        }

        // Associate pipeline with type
        $pipeline->types()->attach($type->id, ['is_default' => true]);

        // Clear cache for this type's pipelines
        $this->clearPipelineCacheForType($typeName);
        $this->clearPipelineCache($pipeline->id);

        return $pipeline;
    }

    public function getPipelinesForType(string $typeName): Collection
    {
        $cacheKey = self::CACHE_KEY_PIPELINES_FOR_TYPE_PREFIX . $typeName;

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($typeName) {
            $type = $this->getTypeConfig($typeName);
            if (!$type) {
                return collect();
            }

            return $type->pipelines()
                ->where('is_active', true)
                ->with(['stages' => function ($query) {
                    $query->orderBy('order');
                }])
                ->orderBy('order')
                ->get();
        });
    }

    public function getDefaultPipelineForType(string $typeName): ?\Modules\Pipeline\Models\Pipeline
    {
        $cacheKey = 'pipeline:type:default:' . $typeName;

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($typeName) {
            $type = $this->getTypeConfig($typeName);
            if (!$type) {
                return null;
            }

            return $type->pipelines()
                ->wherePivot('is_default', true)
                ->where('is_active', true)
                ->with(['stages' => function ($query) {
                    $query->orderBy('order');
                }])
                ->first();
        });
    }

    protected function loadTypes(): void
    {
        $this->types = Cache::remember('pipeline:types:db', self::CACHE_DURATION, function () {
            return PipelineType::where('is_active', true)
                ->orderBy('order')
                ->get();
        });
    }

    // protected function clearPipelineCacheForType(string $typeName): void

    // In Pipeline.php (assuming it's a class)
    // ... existing code ...
    // $typeConfigService = app(TypeConfigService::class); // Or inject via constructor
    // $typeConfigService->clearPipelineCacheForType();
    // ... existing code ...
    public function clearPipelineCacheForType(string $typeName): void
    {
        Cache::forget(self::CACHE_KEY_PIPELINES_FOR_TYPE_PREFIX . $typeName);
        Cache::forget('pipeline:type:default:' . $typeName);
        Cache::forget('pipeline:type:statistics:' . $typeName);
    }

    protected function clearPipelineCache(int $pipelineId): void
    {
        Cache::forget('pipeline:' . $pipelineId);
        Cache::forget('pipeline:' . $pipelineId . ':stages');
        Cache::tags(['pipeline_' . $pipelineId])->flush();
    }
}
