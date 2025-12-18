<?php

namespace Modules\Pipeline\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Pipeline\Models\Pipeline;
use Modules\Pipeline\Models\PipelineType;
use Modules\Pipeline\Config\TypeConfigRegistry;

class PipelineDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Model::unguard();

        $this->call(PipelineTypeSeeder::class);

        // Create default pipelines for each type
        $types = PipelineType::active()->get();

        foreach ($types as $type) {
            $this->createDefaultPipelineForType($type);
        }

        Model::reguard();
    }

    /**
     * Create default pipeline for a type.
     *
     * @param PipelineType $type
     * @return void
     */
    protected function createDefaultPipelineForType(PipelineType $type): void
    {
        // Check if default pipeline already exists
        $defaultPipeline = $type->getDefaultPipeline();

        if ($defaultPipeline) {
            return;
        }

        // Create pipeline
        $pipeline = Pipeline::create([
            'name' => "Default {$type->display_name} Pipeline",
            'description' => "Automatically generated pipeline for {$type->display_name}",
            'is_default' => false,
            'is_active' => true,
            'order' => 0,
            'meta' => [
                'auto_created' => true,
                'type' => $type->name,
            ],
        ]);

        // Add default stages
        $defaultStages = $type->default_stages ?: [];

        if (empty($defaultStages)) {
            // Use generic stages if none defined
            $defaultStages = [
                ['name' => 'New', 'color' => '#f59e0b', 'order' => 0],
                ['name' => 'In Progress', 'color' => '#3b82f6', 'order' => 1],
                ['name' => 'Review', 'color' => '#8b5cf6', 'order' => 2],
                ['name' => 'Completed', 'color' => '#10b981', 'order' => 3],
            ];
        }

        foreach ($defaultStages as $stageData) {
            $stageData['pipeline_id'] = $pipeline->id;
            $pipeline->stages()->create($stageData);
        }

        // Associate pipeline with type as default
        $type->pipelines()->attach($pipeline->id, [
            'is_default' => true,
            'settings' => [
                'auto_assignment' => true,
                'notifications' => true,
            ],
        ]);
    }
}
