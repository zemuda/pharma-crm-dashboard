<?php

namespace Modules\Pipeline\Services;

use Modules\Pipeline\Models\Pipeline;
use Modules\Pipeline\Models\PipelineStage;
use Modules\Pipeline\Models\PipelineType;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class PipelineService
{
    /**
     * @var TypeConfigService
     */
    protected $typeConfigService;

    /**
     * Create a new pipeline service instance.
     *
     * @param TypeConfigService $typeConfigService
     */
    public function __construct(TypeConfigService $typeConfigService)
    {
        $this->typeConfigService = $typeConfigService;
    }

    /**
     * Move an entity to a specific stage in a pipeline.
     *
     * @param string $entityType
     * @param int $entityId
     * @param int $pipelineId
     * @param int $stageId
     * @param array $meta
     * @return bool
     */
    public function moveEntityToStage(string $entityType, int $entityId, int $pipelineId, int $stageId, array $meta = []): bool
    {
        $typeConfig = $this->typeConfigService->getType($entityType);

        if (!$typeConfig) {
            throw new \InvalidArgumentException("Entity type '{$entityType}' is not configured.");
        }

        $table = $typeConfig['table_name'];
        $foreignKey = $typeConfig['foreign_key'];

        // Verify pipeline and stage exist and are valid
        $pipeline = Pipeline::findOrFail($pipelineId);
        $stage = PipelineStage::where('pipeline_id', $pipelineId)->findOrFail($stageId);

        // Get current stage if exists
        $current = DB::table($table)
            ->where($foreignKey, $entityId)
            ->where('pipeline_id', $pipelineId)
            ->first();

        $oldStageId = $current ? $current->pipeline_stage_id : null;

        // Update or insert the entity in the pipeline
        $result = DB::table($table)->updateOrInsert(
            [
                $foreignKey => $entityId,
                'pipeline_id' => $pipelineId,
            ],
            [
                'pipeline_stage_id' => $stageId,
                'meta' => !empty($meta) ? json_encode($meta) : null,
                'stage_changed_at' => now(),
                'updated_at' => now(),
                'created_at' => $current ? $current->created_at : now(),
            ]
        );

        if ($result) {
            // Clear cache
            $this->clearEntityCache($entityType, $entityId, $pipelineId);
            $stage->clearCache();

            if ($oldStageId && $oldStage = PipelineStage::find($oldStageId)) {
                $oldStage->clearCache();
            }

            // Dispatch event
            Event::dispatch('pipeline.entity.moved', [
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'pipeline_id' => $pipelineId,
                'old_stage_id' => $oldStageId,
                'new_stage_id' => $stageId,
                'meta' => $meta,
            ]);

            // Log activity
            $this->logEntityMove($entityType, $entityId, $pipelineId, $oldStageId, $stageId);
        }

        return $result;
    }

    /**
     * Move multiple entities to a stage in bulk.
     *
     * @param string $entityType
     * @param array $entityIds
     * @param int $pipelineId
     * @param int $stageId
     * @param array $meta
     * @return array
     */
    public function moveEntitiesToStage(string $entityType, array $entityIds, int $pipelineId, int $stageId, array $meta = []): array
    {
        $results = [];

        foreach ($entityIds as $entityId) {
            try {
                $results[$entityId] = $this->moveEntityToStage($entityType, $entityId, $pipelineId, $stageId, $meta);
            } catch (\Exception $e) {
                $results[$entityId] = false;
                // Log error
                Log::error("Failed to move entity {$entityType}:{$entityId} to stage {$stageId}: " . $e->getMessage());
            }
        }

        return $results;
    }

    /**
     * Get entity's current pipeline stage.
     *
     * @param string $entityType
     * @param int $entityId
     * @param int|null $pipelineId
     * @return object|null
     */
    public function getEntityPipelineStage(string $entityType, int $entityId, ?int $pipelineId = null): ?object
    {
        $cacheKey = $this->getEntityCacheKey($entityType, $entityId, $pipelineId);

        return Cache::remember($cacheKey, 300, function () use ($entityType, $entityId, $pipelineId) {
            $typeConfig = $this->typeConfigService->getType($entityType);

            if (!$typeConfig) {
                return null;
            }

            $table = $typeConfig['table_name'];
            $foreignKey = $typeConfig['foreign_key'];

            $query = DB::table($table)
                ->select([
                    'pipeline_id',
                    'pipeline_stage_id',
                    'stage_changed_at',
                    'meta',
                    'created_at',
                    'updated_at',
                ])
                ->where($foreignKey, $entityId);

            if ($pipelineId) {
                $query->where('pipeline_id', $pipelineId);
            }

            return $query->first();
        });
    }

    /**
     * Get all pipeline stages with entities for a specific type.
     *
     * @param int $pipelineId
     * @param string $entityType
     * @return \Illuminate\Support\Collection
     */
    public function getPipelineStagesWithEntities(int $pipelineId, string $entityType)
    {
        $cacheKey = "pipeline:{$pipelineId}:stages_with_entities:{$entityType}";

        return Cache::remember($cacheKey, 300, function () use ($pipelineId, $entityType) {
            $pipeline = Pipeline::with(['stages' => function ($query) {
                $query->ordered();
            }])->findOrFail($pipelineId);

            $typeConfig = $this->typeConfigService->getType($entityType);

            if (!$typeConfig) {
                return collect();
            }

            $table = $typeConfig['table_name'];
            $foreignKey = $typeConfig['foreign_key'];

            // Get all entity IDs for this pipeline grouped by stage
            $stageEntities = DB::table($table)
                ->where('pipeline_id', $pipelineId)
                ->select('pipeline_stage_id', $foreignKey)
                ->get()
                ->groupBy('pipeline_stage_id');

            // Load entities for each stage
            foreach ($pipeline->stages as $stage) {
                $entityIds = $stageEntities[$stage->id] ?? collect();
                $stage->entities = $entityIds->pluck($foreignKey)->toArray();
                $stage->entity_count = count($stage->entities);
            }

            return $pipeline->stages;
        });
    }

    /**
     * Create a new pipeline for a specific type.
     *
     * @param string $typeName
     * @param array $data
     * @return Pipeline
     */
    public function createPipelineForType(string $typeName, array $data): Pipeline
    {
        $type = $this->typeConfigService->getTypeConfig($typeName);

        if (!$type) {
            throw new \InvalidArgumentException("Type '{$typeName}' not found.");
        }

        // Create pipeline
        $pipeline = Pipeline::create(array_merge($data, [
            'meta' => array_merge($data['meta'] ?? [], ['type' => $typeName]),
        ]));

        // Add default stages if specified
        if (!empty($data['stages'])) {
            foreach ($data['stages'] as $stageData) {
                $pipeline->stages()->create($stageData);
            }
        } else {
            // Use type's default stages
            $defaultStages = $type->getTypeDefaultStages();
            foreach ($defaultStages as $stageData) {
                $pipeline->stages()->create($stageData);
            }
        }

        // Associate with type
        $isDefault = $data['is_default'] ?? false;
        $type->pipelines()->attach($pipeline->id, [
            'is_default' => $isDefault,
            'settings' => $data['settings'] ?? [],
        ]);

        // Clear cache
        $this->typeConfigService->clearCache();
        $pipeline->clearCache();

        // Dispatch event
        Event::dispatch('pipeline.created', [
            'pipeline' => $pipeline,
            'type' => $typeName,
        ]);

        return $pipeline;
    }

    /**
     * Get statistics for a specific type.
     *
     * @param string $typeName
     * @return array
     */
    public function getTypeStatistics(string $typeName): array
    {
        $cacheKey = "pipeline:type:statistics:{$typeName}";

        return Cache::remember($cacheKey, 300, function () use ($typeName) {
            $type = $this->typeConfigService->getTypeConfig($typeName);

            if (!$type) {
                return [];
            }

            $pipelines = $type->pipelines()->active()->get();
            $table = $type->table_name;

            $totalEntities = 0;
            $pipelineStats = [];

            foreach ($pipelines as $pipeline) {
                // Get entity count
                $entityCount = DB::table($table)
                    ->where('pipeline_id', $pipeline->id)
                    ->count();

                // Get stage distribution
                $stageDistribution = DB::table($table)
                    ->where('pipeline_id', $pipeline->id)
                    ->select('pipeline_stage_id', DB::raw('count(*) as count'))
                    ->groupBy('pipeline_stage_id')
                    ->get()
                    ->pluck('count', 'pipeline_stage_id')
                    ->toArray();

                // Get recent activity
                $recentActivity = DB::table($table)
                    ->where('pipeline_id', $pipeline->id)
                    ->where('stage_changed_at', '>=', now()->subDays(7))
                    ->count();

                $pipelineStats[] = [
                    'pipeline' => [
                        'id' => $pipeline->id,
                        'name' => $pipeline->name,
                        'description' => $pipeline->description,
                    ],
                    'entity_count' => $entityCount,
                    'stage_distribution' => $stageDistribution,
                    'recent_activity' => $recentActivity,
                    'is_default' => $pipeline->isDefaultForType($typeName),
                ];

                $totalEntities += $entityCount;
            }

            // Get overall statistics
            $totalPipelines = $pipelines->count();
            $activeEntities = DB::table($table)
                ->where('stage_changed_at', '>=', now()->subDays(30))
                ->count();

            return [
                'type' => $typeName,
                'display_name' => $type->display_name,
                'total_pipelines' => $totalPipelines,
                'total_entities' => $totalEntities,
                'active_entities' => $activeEntities,
                'pipelines' => $pipelineStats,
                'updated_at' => now(),
            ];
        });
    }

    /**
     * Get dashboard statistics for all types.
     *
     * @return array
     */
    public function getDashboardStatistics(): array
    {
        $cacheKey = 'pipeline:dashboard:statistics';

        return Cache::remember($cacheKey, 300, function () {
            $types = $this->typeConfigService->getActiveTypes();
            $dashboardStats = [];

            foreach ($types as $typeName => $typeConfig) {
                $typeStats = $this->getTypeStatistics($typeName);
                $dashboardStats[$typeName] = [
                    'display_name' => $typeConfig['display_name'],
                    'icon' => $typeConfig['icon'],
                    'color' => $typeConfig['color'],
                    'total_entities' => $typeStats['total_entities'] ?? 0,
                    'active_entities' => $typeStats['active_entities'] ?? 0,
                    'total_pipelines' => $typeStats['total_pipelines'] ?? 0,
                ];
            }

            // Overall statistics
            $totalEntities = array_sum(array_column($dashboardStats, 'total_entities'));
            $activeEntities = array_sum(array_column($dashboardStats, 'active_entities'));
            $totalPipelines = array_sum(array_column($dashboardStats, 'total_pipelines'));

            return [
                'types' => $dashboardStats,
                'overall' => [
                    'total_entities' => $totalEntities,
                    'active_entities' => $activeEntities,
                    'total_pipelines' => $totalPipelines,
                    'type_count' => count($types),
                ],
                'updated_at' => now(),
            ];
        });
    }

    /**
     * Reorder stages in a pipeline.
     *
     * @param int $pipelineId
     * @param array $stageOrder
     * @return bool
     */
    public function reorderStages(int $pipelineId, array $stageOrder): bool
    {
        $pipeline = Pipeline::findOrFail($pipelineId);

        DB::transaction(function () use ($pipelineId, $stageOrder) {
            foreach ($stageOrder as $order => $stageId) {
                DB::table('pipeline_stages')
                    ->where('id', $stageId)
                    ->where('pipeline_id', $pipelineId)
                    ->update(['order' => $order]);
            }
        });

        $pipeline->clearCache();

        return true;
    }

    /**
     * Delete a pipeline and all its associations.
     *
     * @param int $pipelineId
     * @param bool $force
     * @return bool
     */
    public function deletePipeline(int $pipelineId, bool $force = false): bool
    {
        $pipeline = Pipeline::with(['stages', 'types'])->findOrFail($pipelineId);

        // Check if pipeline has entities
        $hasEntities = false;
        foreach ($pipeline->types as $type) {
            $table = $type->table_name;
            $entityCount = DB::table($table)
                ->where('pipeline_id', $pipelineId)
                ->count();

            if ($entityCount > 0) {
                $hasEntities = true;
                break;
            }
        }

        if ($hasEntities && !$force) {
            throw new \RuntimeException('Cannot delete pipeline with entities. Use force delete instead.');
        }

        if ($force) {
            // Delete all entity associations
            foreach ($pipeline->types as $type) {
                $table = $type->table_name;
                DB::table($table)->where('pipeline_id', $pipelineId)->delete();
            }
        }

        $pipeline->delete();

        // Clear cache
        $this->typeConfigService->clearCache();

        return true;
    }

    /**
     * Clear entity cache.
     *
     * @param string $entityType
     * @param int $entityId
     * @param int|null $pipelineId
     * @return void
     */
    protected function clearEntityCache(string $entityType, int $entityId, ?int $pipelineId = null): void
    {
        Cache::forget($this->getEntityCacheKey($entityType, $entityId));

        if ($pipelineId) {
            Cache::forget($this->getEntityCacheKey($entityType, $entityId, $pipelineId));
        }

        // Clear pipeline statistics cache
        Cache::forget("pipeline:type:statistics:{$entityType}");
        Cache::forget("pipeline:{$pipelineId}:stages_with_entities:{$entityType}");
    }

    /**
     * Get entity cache key.
     *
     * @param string $entityType
     * @param int $entityId
     * @param int|null $pipelineId
     * @return string
     */
    protected function getEntityCacheKey(string $entityType, int $entityId, ?int $pipelineId = null): string
    {
        $key = "pipeline:entity:{$entityType}:{$entityId}";

        if ($pipelineId) {
            $key .= ":pipeline:{$pipelineId}";
        }

        return $key;
    }

    /**
     * Log entity move activity.
     *
     * @param string $entityType
     * @param int $entityId
     * @param int $pipelineId
     * @param int|null $oldStageId
     * @param int $newStageId
     * @return void
     */
    protected function logEntityMove(string $entityType, int $entityId, int $pipelineId, ?int $oldStageId, int $newStageId): void
    {
        // You can implement activity logging here
        // For example, using Laravel's activity log package
        // activity()
        //     ->performedOn($entity)
        //     ->withProperties([
        //         'pipeline_id' => $pipelineId,
        //         'old_stage_id' => $oldStageId,
        //         'new_stage_id' => $newStageId,
        //     ])
        //     ->log('moved_in_pipeline');
    }
}
