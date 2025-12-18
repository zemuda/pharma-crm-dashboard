<?php

namespace Modules\Pipeline\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Modules\Pipeline\Database\Seeders\PipelineDatabaseSeeder;

class PipelineInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pipeline:install
                            {--fresh : Fresh migration}
                            {--seed : Seed the database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Pipeline module';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('Installing Pipeline Module...');

        // Publish configuration
        $this->info('Publishing configuration...');
        Artisan::call('vendor:publish', ['--tag' => 'pipeline-config']);

        // Run migrations
        $this->info('Running migrations...');

        if ($this->option('fresh')) {
            Artisan::call('module:migrate-reset', ['module' => 'Pipeline']);
        }

        Artisan::call('module:migrate', ['module' => 'Pipeline']);

        // Seed database
        if ($this->option('seed')) {
            $this->info('Seeding database...');
            Artisan::call('module:seed', ['module' => 'Pipeline']);
        }

        // Sync pipeline types
        $this->info('Syncing pipeline types...');
        Artisan::call('pipeline:sync-types');

        $this->info('Pipeline module installed successfully!');

        $this->line('');
        $this->info('Available commands:');
        $this->line('  php artisan pipeline:sync-types        - Sync pipeline types');
        $this->line('  php artisan pipeline:cache-clear       - Clear pipeline cache');
        $this->line('  php artisan pipeline:stats             - Show pipeline statistics');

        $this->line('');
        $this->info('To use pipelines in your models:');
        $this->line('  1. Add "use Modules\Pipeline\Traits\HasPipeline;"');
        $this->line('  2. Add "use HasPipeline;" in your model');
        $this->line('  3. Implement Pipelineable interface (optional)');

        return Command::SUCCESS;
    }
}
