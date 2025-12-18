Modules/Pipeline/
├── Config/
│   ├── config.php
│   ├── pipeline-cache.php
│   └── TypeConfigRegistry.php
├── Console/
│   └── Commands/
│       ├── PipelineInstallCommand.php
│       ├── PipelineSyncTypesCommand.php
│       └── PipelineCacheClearCommand.php
├── Database/
│   ├── Migrations/
│   │   ├── 2024_01_01_000001_create_pipelines_table.php
│   │   ├── 2024_01_01_000002_create_pipeline_stages_table.php
│   │   ├── 2024_01_01_000003_create_pipeline_types_table.php
│   │   ├── 2024_01_01_000004_create_pipeline_type_associations_table.php
│   │   └── 2024_01_01_000005_create_pipelineable_tables.php
│   ├── Seeders/
│   │   ├── PipelineDatabaseSeeder.php
│   │   └── PipelineTypeSeeder.php
│   └── Factories/
│       ├── PipelineFactory.php
│       ├── PipelineStageFactory.php
│       └── PipelineTypeFactory.php
├── Entities/
│   ├── Pipeline.php
│   ├── PipelineStage.php
│   └── PipelineType.php
├── Http/
│   ├── Controllers/
│   │   ├── PipelineController.php
│   │   ├── PipelineStageController.php
│   │   └── TypeConfigController.php
│   ├── Requests/
│   │   ├── StorePipelineRequest.php
│   │   ├── UpdatePipelineRequest.php
│   │   ├── StorePipelineStageRequest.php
│   │   └── UpdatePipelineStageRequest.php
│   ├── Resources/
│   │   ├── PipelineResource.php
│   │   ├── PipelineCollection.php
│   │   ├── PipelineStageResource.php
│   │   └── PipelineTypeResource.php
│   └── Middleware/
│       └── CheckPipelineAccess.php
├── Providers/
│   ├── PipelineServiceProvider.php
│   ├── EventServiceProvider.php
│   └── RouteServiceProvider.php
├── Repositories/
│   ├── PipelineRepository.php
│   ├── PipelineStageRepository.php
│   ├── PipelineTypeRepository.php
│   └── Eloquent/
│       ├── PipelineRepositoryEloquent.php
│       ├── PipelineStageRepositoryEloquent.php
│       └── PipelineTypeRepositoryEloquent.php
├── Services/
│   ├── PipelineService.php
│   ├── TypeConfigService.php
│   └── PipelineStatisticsService.php
├── Traits/
│   └── HasPipeline.php
├── Contracts/
│   └── Pipelineable.php
├── Events/
│   ├── EntityMovedToStage.php
│   ├── PipelineCreated.php
│   └── PipelineStageUpdated.php
├── Listeners/
│   ├── SendEntityMoveNotification.php
│   └── UpdatePipelineStatistics.php
├── Jobs/
│   └── ProcessBulkPipelineMove.php
├── Resources/
│   └── views/
│       └── email/
│           └── entity-moved.blade.php
├── Rules/
│   ├── ValidPipelineStage.php
│   └── ValidPipelineType.php
├── Tests/
│   ├── Unit/
│   │   ├── PipelineTest.php
│   │   └── PipelineServiceTest.php
│   └── Feature/
│       ├── PipelineControllerTest.php
│       └── TypeConfigControllerTest.php
├── helpers.php
└── webpack.mix.js