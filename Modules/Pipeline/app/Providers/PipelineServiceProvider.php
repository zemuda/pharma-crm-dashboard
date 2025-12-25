<?php

namespace Modules\Pipeline\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Pipeline\Services\PipelineService;
use Modules\Pipeline\Services\TypeConfigService;
use Modules\Pipeline\Services\PipelineStatisticsService;
use Modules\Pipeline\Repositories\PipelineRepository;
use Modules\Pipeline\Repositories\PipelineStageRepository;
use Modules\Pipeline\Repositories\PipelineTypeRepository;
use Modules\Pipeline\Repositories\Eloquent\PipelineRepositoryEloquent;
use Modules\Pipeline\Repositories\Eloquent\PipelineStageRepositoryEloquent;
use Modules\Pipeline\Repositories\Eloquent\PipelineTypeRepositoryEloquent;
use Modules\Pipeline\Config\TypeConfigRegistry;
use Modules\Pipeline\Console\Commands\PipelineInstallCommand;
use Modules\Pipeline\Console\Commands\PipelineSyncTypesCommand;
use Modules\Pipeline\Console\Commands\PipelineCacheClearCommand;
use Modules\Pipeline\Console\Commands\PipelineStatsCommand;

class PipelineServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Pipeline';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'pipeline';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->registerCommands();
        $this->registerPublishes();
        $this->registerMacros();
        $this->registerObservers();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);

        $this->registerServices();
        $this->registerRepositories();
        $this->registerHelpers();
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
            module_path($this->moduleName, 'Config/pipeline-cache.php') => config_path($this->moduleNameLower . '-cache.php'),
        ], 'pipeline-config');

        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'),
            $this->moduleNameLower
        );

        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/pipeline-cache.php'),
            $this->moduleNameLower . '-cache'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'Resources/lang'));
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    protected function registerServices(): void
    {
        // Register TypeConfigService as singleton
        $this->app->singleton(TypeConfigService::class, function ($app) {
            $service = new TypeConfigService();
            // Register default types
            TypeConfigRegistry::registerDefaults($service);
            return $service;
        });

        // Register PipelineService
        $this->app->singleton(PipelineService::class, function ($app) {
            return new PipelineService(
                $app->make(TypeConfigService::class),
                $app->make(PipelineRepository::class),
                $app->make(PipelineStageRepository::class)
            );
        });

        // Register PipelineStatisticsService
        $this->app->singleton(PipelineStatisticsService::class, function ($app) {
            return new PipelineStatisticsService(
                $app->make(TypeConfigService::class)
            );
        });

        // Register PipelineService as a facade alias
        $this->app->alias(PipelineService::class, 'pipeline.service');
        $this->app->alias(TypeConfigService::class, 'pipeline.type-config');
        $this->app->alias(PipelineStatisticsService::class, 'pipeline.statistics');
    }

    /**
     * Register repositories.
     *
     * @return void
     */
    protected function registerRepositories(): void
    {
        // Bind repositories to their eloquent implementations
        $this->app->bind(PipelineRepository::class, function ($app) {
            return new PipelineRepositoryEloquent(
                $app->make(\Modules\Pipeline\Entities\Pipeline::class)
            );
        });

        $this->app->bind(PipelineStageRepository::class, function ($app) {
            return new PipelineStageRepositoryEloquent(
                $app->make(\Modules\Pipeline\Entities\PipelineStage::class)
            );
        });

        $this->app->bind(PipelineTypeRepository::class, function ($app) {
            return new PipelineTypeRepositoryEloquent(
                $app->make(\Modules\Pipeline\Entities\PipelineType::class)
            );
        });
    }

    /**
     * Register console commands.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        $this->commands([
            PipelineInstallCommand::class,
            PipelineSyncTypesCommand::class,
            PipelineCacheClearCommand::class,
            PipelineStatsCommand::class,
        ]);
    }

    /**
     * Register publishable resources.
     *
     * @return void
     */
    protected function registerPublishes(): void
    {
        // Publish migrations
        $this->publishes([
            module_path($this->moduleName, 'Database/Migrations') => database_path('migrations'),
        ], 'pipeline-migrations');

        // Publish seeders
        $this->publishes([
            module_path($this->moduleName, 'Database/Seeders') => database_path('seeders'),
        ], 'pipeline-seeders');

        // Publish factories
        $this->publishes([
            module_path($this->moduleName, 'Database/Factories') => database_path('factories'),
        ], 'pipeline-factories');

        // Publish tests
        $this->publishes([
            module_path($this->moduleName, 'Tests') => base_path('tests/Modules/Pipeline'),
        ], 'pipeline-tests');

        // Publish API documentation
        $this->publishes([
            module_path($this->moduleName, 'Resources/docs') => base_path('docs/pipeline'),
        ], 'pipeline-docs');
    }

    /**
     * Register helper functions.
     *
     * @return void
     */
    protected function registerHelpers(): void
    {
        $helperFile = module_path($this->moduleName, 'helpers.php');

        if (file_exists($helperFile)) {
            require_once $helperFile;
        }
    }

    /**
     * Register macros.
     *
     * @return void
     */
    protected function registerMacros(): void
    {
        // Register collection macros for pipeline operations
        \Illuminate\Support\Collection::macro('groupByPipelineStage', function ($typeName) {
            $typeService = app(TypeConfigService::class);
            $type = $typeService->getTypeConfig($typeName);

            if (!$type) {
                return $this->groupBy(function ($item) {
                    return 'unknown';
                });
            }

            $table = $type->table_name;
            $foreignKey = $type->foreign_key;

            // Get pipeline stage mapping for these entities
            $entityIds = $this->pluck('id')->toArray();

            if (empty($entityIds)) {
                return collect();
            }

            $stageMapping = \DB::table($table)
                ->whereIn($foreignKey, $entityIds)
                ->pluck('pipeline_stage_id', $foreignKey)
                ->toArray();

            return $this->groupBy(function ($item) use ($stageMapping) {
                return $stageMapping[$item->id] ?? 'unassigned';
            });
        });

        // Register query builder macro for pipeline filtering
        \Illuminate\Database\Eloquent\Builder::macro('whereInPipeline', function ($pipelineId) {
            $model = $this->getModel();

            if (!method_exists($model, 'getPipelineTable') || !method_exists($model, 'getPipelineForeignKey')) {
                throw new \Exception('Model does not use HasPipeline trait');
            }

            $table = $model->getPipelineTable();
            $foreignKey = $model->getPipelineForeignKey();

            return $this->whereIn($model->getKeyName(), function ($query) use ($table, $foreignKey, $pipelineId) {
                $query->select($foreignKey)
                    ->from($table)
                    ->where('pipeline_id', $pipelineId);
            });
        });

        // Register query builder macro for stage filtering
        \Illuminate\Database\Eloquent\Builder::macro('whereInPipelineStage', function ($stageId) {
            $model = $this->getModel();

            if (!method_exists($model, 'getPipelineTable') || !method_exists($model, 'getPipelineForeignKey')) {
                throw new \Exception('Model does not use HasPipeline trait');
            }

            $table = $model->getPipelineTable();
            $foreignKey = $model->getPipelineForeignKey();

            return $this->whereIn($model->getKeyName(), function ($query) use ($table, $foreignKey, $stageId) {
                $query->select($foreignKey)
                    ->from($table)
                    ->where('pipeline_stage_id', $stageId);
            });
        });
    }

    /**
     * Register model observers.
     *
     * @return void
     */
    protected function registerObservers(): void
    {
        // Register pipeline observers if needed
        // \Modules\Pipeline\Entities\Pipeline::observe(\Modules\Pipeline\Observers\PipelineObserver::class);
        // \Modules\Pipeline\Entities\PipelineStage::observe(\Modules\Pipeline\Observers\PipelineStageObserver::class);
        // \Modules\Pipeline\Entities\PipelineType::observe(\Modules\Pipeline\Observers\PipelineTypeObserver::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            PipelineService::class,
            TypeConfigService::class,
            PipelineStatisticsService::class,
            PipelineRepository::class,
            PipelineStageRepository::class,
            PipelineTypeRepository::class,
            PipelineInstallCommand::class,
            PipelineSyncTypesCommand::class,
            PipelineCacheClearCommand::class,
            PipelineStatsCommand::class,
        ];
    }

    /**
     * Get publishable view paths.
     *
     * @return array
     */
    private function getPublishableViewPaths(): array
    {
        $paths = [];

        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }

        return $paths;
    }
}
