<?php

namespace Modules\Pipeline\Console;

use Illuminate\Console\Command;
use Modules\Pipeline\Services\TypeConfigService;

class PipelineSyncTypesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pipeline:sync-types
                            {--verify : Verify model classes exist}
                            {--list : List all registered types}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync pipeline types configuration';

    /**
     * Execute the console command.
     *
     * @param TypeConfigService $service
     * @return int
     */
    public function handle(TypeConfigService $service): int
    {
        $this->info('Syncing pipeline types...');

        // Sync types
        $service->syncTypes();

        $this->info('✓ Types synchronized successfully');

        // List types if requested
        if ($this->option('list')) {
            $this->listTypes($service);
        }

        // Verify model classes if requested
        if ($this->option('verify')) {
            $this->verifyModelClasses($service);
        }

        return Command::SUCCESS;
    }

    /**
     * List all registered types.
     *
     * @param TypeConfigService $service
     * @return void
     */
    protected function listTypes(TypeConfigService $service): void
    {
        $types = $service->getActiveTypes();

        $this->info("\nRegistered Pipeline Types:");
        $this->table(
            ['Name', 'Display Name', 'Active', 'Model Class', 'Capabilities'],
            collect($types)->map(function ($type, $name) {
                $capabilities = [];
                if ($type['has_probability']) $capabilities[] = 'probability';
                if ($type['has_value']) $capabilities[] = 'value';
                if ($type['has_due_date']) $capabilities[] = 'due_date';
                if ($type['has_priority']) $capabilities[] = 'priority';

                return [
                    $name,
                    $type['display_name'],
                    $type['is_active'] ? '✓' : '✗',
                    $type['model_class'] ?? 'Auto-detected',
                    implode(', ', $capabilities),
                ];
            })->toArray()
        );
    }

    /**
     * Verify model classes exist.
     *
     * @param TypeConfigService $service
     * @return void
     */
    protected function verifyModelClasses(TypeConfigService $service): void
    {
        $this->info("\nVerifying model classes:");

        $types = $service->getTypes();
        $rows = [];

        foreach ($types as $name => $config) {
            $typeModel = \Modules\Pipeline\Models\PipelineType::where('name', $name)->first();

            if (!$typeModel) {
                $rows[] = [$name, '✗', 'Type not found in database'];
                continue;
            }

            $modelInstance = $typeModel->getModelInstance();

            if ($modelInstance) {
                $rows[] = [$name, '✓', get_class($modelInstance)];
            } else {
                $rows[] = [$name, '✗', 'Model not found'];
            }
        }

        $this->table(['Type', 'Status', 'Model Class'], $rows);
    }
}
