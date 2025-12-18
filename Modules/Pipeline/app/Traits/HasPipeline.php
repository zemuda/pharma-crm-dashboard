<?php

namespace Modules\Pipeline\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Pipeline\Models\Pipeline;
use Modules\Pipeline\Models\PipelineStage;
use Modules\Pipeline\Services\TypeConfigService;

trait HasPipeline
{
    /**
     * Boot the trait.
     *
     * @return void
     */
    protected static function bootHasPipeline(): void
    {
        static::created(function ($model) {
            // Auto-assign to default pipeline when entity is created
            $model->assignToDefaultPipeline();
        });
    }

    /**
     * Get the pipeline type name for this model.
     *
     * @return string
     */
    protected function getPipelineTypeName(): string
    {
        // Use model's table name (singular) as type name
        // e.g., 'deals' table -> 'deal' type
        return Str::singular($this->getTable());
    }

    /**
     * Get the pipeline pivot table name for this model.
     *
     * @return string
     */
    protected function getPipelineTable(): string
    {
        $typeName = $this->getPipelineTypeName();
        $typeConfig = app(TypeConfigService::class)->getType($typeName);

        return $typeConfig['table_name'] ?? $typeName . '_pipeline';
    }

    /**
     * Get the foreign key name for this model.
     *
     * @return string
     */
    protected function getPipelineForeignKey(): string
    {
        $typeName = $this->getPipelineTypeName();
        $typeConfig = app(TypeConfigService::class)->getType($typeName);

        return $typeConfig['foreign_key'] ?? $typeName . '_id';
    }

    /**
     * Get the pipelines associated with the model.
     *
     * @return BelongsToMany
     */
    public function pipelines(): BelongsToMany
    {
        return $this->belongsToMany(
            Pipeline::class,
            $this->getPipelineTable(),
            $this->getPipelineForeignKey(),
            'pipeline_id'
        )
            ->withPivot(['pipeline_stage_id', 'meta', 'stage_changed_at'])
            ->withTimestamps();
    }

    /**
     * Get the pipeline stages associated with the model.
     *
     * @return BelongsToMany
     */
    public function pipelineStages(): BelongsToMany
    {
        return $this->belongsToMany(
            PipelineStage::class,
            $this->getPipelineTable(),
            $this->getPipelineForeignKey(),
            'pipeline_stage_id'
        )
            ->withTimestamps();
    }

    /**
     * Get the current pipeline for the model.
     *
     * @return Pipeline|null
     */
    public function currentPipeline(): ?Pipeline
    {
        return $this->pipelines()->latest()->first();
    }

    /**
     * Get the current pipeline stage for the model.
     *
     * @return PipelineStage|null
     */
    public function currentStage(): ?PipelineStage
    {
        $pipeline = $this->currentPipeline();

        if (!$pipeline) {
            return null;
        }

        $pivot = $this->pipelines()->where('pipeline_id', $pipeline->id)->first();

        if (!$pivot || !$pivot->pivot->pipeline_stage_id) {
            return null;
        }

        return PipelineStage::find($pivot->pivot->pipeline_stage_id);
    }

    /**
     * Move the model to a specific pipeline and stage.
     *
     * @param int $pipelineId
     * @param int|null $stageId
     * @param array $meta
     * @return $this
     */
    public function moveToPipeline(int $pipelineId, ?int $stageId = null, array $meta = []): self
    {
        $pipeline = Pipeline::findOrFail($pipelineId);

        if (!$stageId) {
            $stageId = $pipeline->firstStage()->id ?? null;

            if (!$stageId) {
                throw new \RuntimeException('Pipeline has no stages.');
            }
        }

        // Verify stage belongs to pipeline
        $stage = $pipeline->stages()->findOrFail($stageId);

        $this->pipelines()->sync([$pipelineId => [
            'pipeline_stage_id' => $stageId,
            'meta' => $meta ? json_encode($meta) : null,
            'stage_changed_at' => now(),
        ]], false);

        $this->load('pipelines');

        return $this;
    }

    /**
     * Move the model to a specific stage in the current pipeline.
     *
     * @param int $stageId
     * @param array $meta
     * @return $this
     */
    public function moveToStage(int $stageId, array $meta = []): self
    {
        $currentPipeline = $this->currentPipeline();

        if (!$currentPipeline) {
            throw new \RuntimeException('Model is not in any pipeline.');
        }

        // Verify stage belongs to current pipeline
        $stage = $currentPipeline->stages()->findOrFail($stageId);

        $this->pipelines()->updateExistingPivot($currentPipeline->id, [
            'pipeline_stage_id' => $stageId,
            'meta' => $meta ? json_encode($meta) : null,
            'stage_changed_at' => now(),
            'updated_at' => now(),
        ]);

        $this->load('pipelines');

        return $this;
    }

    /**
     * Move the model to the next stage in the current pipeline.
     *
     * @param array $meta
     * @return $this
     */
    public function moveToNextStage(array $meta = []): self
    {
        $currentStage = $this->currentStage();

        if (!$currentStage) {
            throw new \RuntimeException('Model is not in any pipeline stage.');
        }

        $nextStage = $currentStage->nextStage();

        if (!$nextStage) {
            throw new \RuntimeException('No next stage available.');
        }

        return $this->moveToStage($nextStage->id, $meta);
    }

    /**
     * Move the model to the previous stage in the current pipeline.
     *
     * @param array $meta
     * @return $this
     */
    public function moveToPreviousStage(array $meta = []): self
    {
        $currentStage = $this->currentStage();

        if (!$currentStage) {
            throw new \RuntimeException('Model is not in any pipeline stage.');
        }

        $previousStage = $currentStage->previousStage();

        if (!$previousStage) {
            throw new \RuntimeException('No previous stage available.');
        }

        return $this->moveToStage($previousStage->id, $meta);
    }

    /**
     * Check if the model is in a specific pipeline.
     *
     * @param int $pipelineId
     * @return bool
     */
    public function isInPipeline(int $pipelineId): bool
    {
        return $this->pipelines()->where('pipeline_id', $pipelineId)->exists();
    }

    /**
     * Check if the model is in a specific stage.
     *
     * @param int $stageId
     * @return bool
     */
    public function isInStage(int $stageId): bool
    {
        return $this->pipelineStages()->where('pipeline_stage_id', $stageId)->exists();
    }

    /**
     * Get the time spent in current stage.
     *
     * @return int|null Number of hours, or null if not in a pipeline
     */
    public function getTimeInCurrentStage(): ?int
    {
        $pivot = $this->pipelines()->first();

        if (!$pivot || !$pivot->pivot->stage_changed_at) {
            return null;
        }

        return now()->diffInHours($pivot->pivot->stage_changed_at);
    }

    /**
     * Assign the model to its default pipeline.
     *
     * @return $this
     */
    public function assignToDefaultPipeline(): self
    {
        $typeName = $this->getPipelineTypeName();
        $service = app(TypeConfigService::class);

        $defaultPipeline = $service->getDefaultPipelineForType($typeName);

        if ($defaultPipeline) {
            $this->moveToPipeline($defaultPipeline->id);
        }

        return $this;
    }

    /**
     * Get pipeline history for the model.
     *
     * @return array
     */
    public function getPipelineHistory(): array
    {
        $table = $this->getPipelineTable();
        $foreignKey = $this->getPipelineForeignKey();

        return DB::table($table)
            ->where($foreignKey, $this->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($record) {
                return [
                    'pipeline_id' => $record->pipeline_id,
                    'stage_id' => $record->pipeline_stage_id,
                    'meta' => json_decode($record->meta, true),
                    'stage_changed_at' => $record->stage_changed_at,
                    'created_at' => $record->created_at,
                    'updated_at' => $record->updated_at,
                ];
            })
            ->toArray();
    }

    /**
     * Scope query to models in a specific pipeline.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $pipelineId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInPipeline($query, int $pipelineId)
    {
        $table = $this->getPipelineTable();
        $foreignKey = $this->getPipelineForeignKey();

        return $query->whereIn($this->getKeyName(), function ($subquery) use ($table, $foreignKey, $pipelineId) {
            $subquery->select($foreignKey)
                ->from($table)
                ->where('pipeline_id', $pipelineId);
        });
    }

    /**
     * Scope query to models in a specific stage.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $stageId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInStage($query, int $stageId)
    {
        $table = $this->getPipelineTable();
        $foreignKey = $this->getPipelineForeignKey();

        return $query->whereIn($this->getKeyName(), function ($subquery) use ($table, $foreignKey, $stageId) {
            $subquery->select($foreignKey)
                ->from($table)
                ->where('pipeline_stage_id', $stageId);
        });
    }

    /**
     * Scope query to models in a specific pipeline stage.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $pipelineId
     * @param int $stageId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInPipelineStage($query, int $pipelineId, int $stageId)
    {
        $table = $this->getPipelineTable();
        $foreignKey = $this->getPipelineForeignKey();

        return $query->whereIn($this->getKeyName(), function ($subquery) use ($table, $foreignKey, $pipelineId, $stageId) {
            $subquery->select($foreignKey)
                ->from($table)
                ->where('pipeline_id', $pipelineId)
                ->where('pipeline_stage_id', $stageId);
        });
    }
}
