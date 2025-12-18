<?php

namespace Modules\Pipeline\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PipelineStatisticsService
{
    /**
     * @var TypeConfigService
     */
    protected $typeConfigService;

    /**
     * Create a new statistics service instance.
     *
     * @param TypeConfigService $typeConfigService
     */
    public function __construct(TypeConfigService $typeConfigService)
    {
        $this->typeConfigService = $typeConfigService;
    }

    /**
     * Get conversion rates for a pipeline.
     *
     * @param int $pipelineId
     * @param string $typeName
     * @return array
     */
    public function getConversionRates(int $pipelineId, string $typeName): array
    {
        $cacheKey = "pipeline:{$pipelineId}:conversion_rates:{$typeName}";

        return Cache::remember($cacheKey, 3600, function () use ($pipelineId, $typeName) {
            $type = $this->typeConfigService->getTypeConfig($typeName);

            if (!$type) {
                return [];
            }

            $table = $type->table_name;

            // Get stage distribution
            $stages = DB::table('pipeline_stages')
                ->where('pipeline_id', $pipelineId)
                ->orderBy('order')
                ->get(['id', 'name', 'probability']);

            $stageData = [];
            $totalEntities = 0;

            foreach ($stages as $stage) {
                $entityCount = DB::table($table)
                    ->where('pipeline_id', $pipelineId)
                    ->where('pipeline_stage_id', $stage->id)
                    ->count();

                $stageData[] = [
                    'id' => $stage->id,
                    'name' => $stage->name,
                    'probability' => $stage->probability,
                    'entity_count' => $entityCount,
                    'percentage' => 0, // Will be calculated after total
                ];

                $totalEntities += $entityCount;
            }

            // Calculate percentages
            if ($totalEntities > 0) {
                foreach ($stageData as &$stage) {
                    $stage['percentage'] = round(($stage['entity_count'] / $totalEntities) * 100, 2);
                }
            }

            // Calculate weighted pipeline value
            $pipelineValue = 0;
            foreach ($stageData as $stage) {
                if ($stage['probability'] && $type->has_value) {
                    // This would need actual entity values to calculate
                    // For now, we'll use probability * entity_count
                    $pipelineValue += $stage['probability'] * $stage['entity_count'];
                }
            }

            return [
                'stages' => $stageData,
                'total_entities' => $totalEntities,
                'weighted_value' => $pipelineValue,
                'average_probability' => $totalEntities > 0 ? $pipelineValue / $totalEntities : 0,
                'updated_at' => now(),
            ];
        });
    }

    /**
     * Get time in stage statistics.
     *
     * @param string $typeName
     * @param int $days
     * @return array
     */
    public function getTimeInStageStats(string $typeName, int $days = 30): array
    {
        $cacheKey = "pipeline:time_in_stage:{$typeName}:{$days}";

        return Cache::remember($cacheKey, 1800, function () use ($typeName, $days) {
            $type = $this->typeConfigService->getTypeConfig($typeName);

            if (!$type) {
                return [];
            }

            $table = $type->table_name;

            // Get average time in each stage for completed entities
            $stats = DB::table($table)
                ->select([
                    'pipeline_stage_id',
                    DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, stage_changed_at)) as avg_hours'),
                    DB::raw('MIN(TIMESTAMPDIFF(HOUR, created_at, stage_changed_at)) as min_hours'),
                    DB::raw('MAX(TIMESTAMPDIFF(HOUR, created_at, stage_changed_at)) as max_hours'),
                    DB::raw('COUNT(*) as entity_count'),
                ])
                ->where('stage_changed_at', '>=', now()->subDays($days))
                ->whereNotNull('stage_changed_at')
                ->groupBy('pipeline_stage_id')
                ->get();

            // Get stage names
            $stageNames = DB::table('pipeline_stages')
                ->whereIn('id', $stats->pluck('pipeline_stage_id'))
                ->pluck('name', 'id')
                ->toArray();

            $result = [];
            foreach ($stats as $stat) {
                $result[] = [
                    'stage_id' => $stat->pipeline_stage_id,
                    'stage_name' => $stageNames[$stat->pipeline_stage_id] ?? 'Unknown',
                    'avg_hours' => round($stat->avg_hours, 2),
                    'min_hours' => $stat->min_hours,
                    'max_hours' => $stat->max_hours,
                    'entity_count' => $stat->entity_count,
                ];
            }

            return [
                'stats' => $result,
                'period_days' => $days,
                'total_entities' => $stats->sum('entity_count'),
                'updated_at' => now(),
            ];
        });
    }

    /**
     * Get pipeline velocity.
     *
     * @param int $pipelineId
     * @param string $typeName
     * @param int $days
     * @return array
     */
    public function getPipelineVelocity(int $pipelineId, string $typeName, int $days = 30): array
    {
        $cacheKey = "pipeline:{$pipelineId}:velocity:{$typeName}:{$days}";

        return Cache::remember($cacheKey, 1800, function () use ($pipelineId, $typeName, $days) {
            $type = $this->typeConfigService->getTypeConfig($typeName);

            if (!$type) {
                return [];
            }

            $table = $type->table_name;

            // Get entities that completed the pipeline (reached final stage)
            $finalStages = DB::table('pipeline_stages')
                ->where('pipeline_id', $pipelineId)
                ->whereJsonContains('meta->is_final', true)
                ->pluck('id');

            if ($finalStages->isEmpty()) {
                return [];
            }

            $completedEntities = DB::table($table)
                ->where('pipeline_id', $pipelineId)
                ->whereIn('pipeline_stage_id', $finalStages)
                ->where('stage_changed_at', '>=', now()->subDays($days))
                ->select([
                    DB::raw('DATE(stage_changed_at) as completion_date'),
                    DB::raw('COUNT(*) as count'),
                ])
                ->groupBy('completion_date')
                ->orderBy('completion_date')
                ->get();

            // Calculate average completion time
            $avgCompletionTime = DB::table($table)
                ->where('pipeline_id', $pipelineId)
                ->whereIn('pipeline_stage_id', $finalStages)
                ->where('stage_changed_at', '>=', now()->subDays($days))
                ->avg(DB::raw('TIMESTAMPDIFF(HOUR, created_at, stage_changed_at)'));

            return [
                'daily_completions' => $completedEntities,
                'total_completions' => $completedEntities->sum('count'),
                'avg_completion_hours' => round($avgCompletionTime, 2),
                'period_days' => $days,
                'updated_at' => now(),
            ];
        });
    }

    /**
     * Get forecast accuracy.
     *
     * @param string $typeName
     * @param int $days
     * @return array
     */
    public function getForecastAccuracy(string $typeName, int $days = 90): array
    {
        if (!$this->typeConfigService->getType($typeName)['has_probability'] ?? false) {
            return [];
        }

        $cacheKey = "pipeline:forecast_accuracy:{$typeName}:{$days}";

        return Cache::remember($cacheKey, 3600, function () use ($typeName, $days) {
            // This is a simplified example
            // In a real application, you would compare forecasted vs actual outcomes
            return [
                'period_days' => $days,
                'accuracy_rate' => 0, // Placeholder
                'updated_at' => now(),
            ];
        });
    }

    /**
     * Clear all statistics cache.
     *
     * @return void
     */
    public function clearCache(): void
    {
        // Clear all statistics cache keys
        if (config('cache.default') === 'redis') {
            $patterns = [
                'pipeline:*:conversion_rates:*',
                'pipeline:time_in_stage:*',
                'pipeline:*:velocity:*',
                'pipeline:forecast_accuracy:*',
                'pipeline:dashboard:statistics',
                'pipeline:type:statistics:*',
            ];

            foreach ($patterns as $pattern) {
                $keys = Cache::getRedis()->keys($pattern);
                if (!empty($keys)) {
                    Cache::getRedis()->del($keys);
                }
            }
        }
    }
}
