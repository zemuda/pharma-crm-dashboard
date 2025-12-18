<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Deal pipeline association
        if (!Schema::hasTable('deal_pipeline')) {
            Schema::create('deal_pipeline', function (Blueprint $table) {
                $table->id();
                $table->foreignId('deal_id')
                    ->constrained('deals')
                    ->onDelete('cascade');
                $table->foreignId('pipeline_id')
                    ->constrained()
                    ->onDelete('cascade');
                $table->foreignId('pipeline_stage_id')
                    ->constrained('pipeline_stages')
                    ->onDelete('cascade');
                $table->json('meta')->nullable();
                $table->timestamp('stage_changed_at')->nullable();
                $table->timestamps();

                $table->unique(['deal_id', 'pipeline_id']);
                $table->index(['pipeline_id', 'pipeline_stage_id']);
                $table->index('stage_changed_at');
            });
        }

        // Task pipeline association
        // if (!Schema::hasTable('task_pipeline')) {
        //     Schema::create('task_pipeline', function (Blueprint $table) {
        //         $table->id();
        //         $table->foreignId('task_id')
        //             ->constrained('tasks')
        //             ->onDelete('cascade');
        //         $table->foreignId('pipeline_id')
        //             ->constrained()
        //             ->onDelete('cascade');
        //         $table->foreignId('pipeline_stage_id')
        //             ->constrained('pipeline_stages')
        //             ->onDelete('cascade');
        //         $table->json('meta')->nullable();
        //         $table->timestamp('stage_changed_at')->nullable();
        //         $table->timestamps();

        //         $table->unique(['task_id', 'pipeline_id']);
        //         $table->index(['pipeline_id', 'pipeline_stage_id']);
        //     });
        // }

        // // Lead pipeline association
        // if (!Schema::hasTable('lead_pipeline')) {
        //     Schema::create('lead_pipeline', function (Blueprint $table) {
        //         $table->id();
        //         $table->foreignId('lead_id')
        //             ->constrained('leads')
        //             ->onDelete('cascade');
        //         $table->foreignId('pipeline_id')
        //             ->constrained()
        //             ->onDelete('cascade');
        //         $table->foreignId('pipeline_stage_id')
        //             ->constrained('pipeline_stages')
        //             ->onDelete('cascade');
        //         $table->json('meta')->nullable();
        //         $table->timestamp('stage_changed_at')->nullable();
        //         $table->timestamps();

        //         $table->unique(['lead_id', 'pipeline_id']);
        //         $table->index(['pipeline_id', 'pipeline_stage_id']);
        //     });
        // }

        // // Ticket pipeline association
        // if (!Schema::hasTable('ticket_pipeline')) {
        //     Schema::create('ticket_pipeline', function (Blueprint $table) {
        //         $table->id();
        //         $table->foreignId('ticket_id')
        //             ->constrained('tickets')
        //             ->onDelete('cascade');
        //         $table->foreignId('pipeline_id')
        //             ->constrained()
        //             ->onDelete('cascade');
        //         $table->foreignId('pipeline_stage_id')
        //             ->constrained('pipeline_stages')
        //             ->onDelete('cascade');
        //         $table->json('meta')->nullable();
        //         $table->timestamp('stage_changed_at')->nullable();
        //         $table->timestamps();

        //         $table->unique(['ticket_id', 'pipeline_id']);
        //         $table->index(['pipeline_id', 'pipeline_stage_id']);
        //     });
        // }

        // // Support pipeline association
        // if (!Schema::hasTable('support_pipeline')) {
        //     Schema::create('support_pipeline', function (Blueprint $table) {
        //         $table->id();
        //         $table->foreignId('support_id')
        //             ->constrained('support_cases')
        //             ->onDelete('cascade');
        //         $table->foreignId('pipeline_id')
        //             ->constrained()
        //             ->onDelete('cascade');
        //         $table->foreignId('pipeline_stage_id')
        //             ->constrained('pipeline_stages')
        //             ->onDelete('cascade');
        //         $table->json('meta')->nullable();
        //         $table->timestamp('stage_changed_at')->nullable();
        //         $table->timestamps();

        //         $table->unique(['support_id', 'pipeline_id']);
        //         $table->index(['pipeline_id', 'pipeline_stage_id']);
        //     });
        // }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('support_pipeline');
        // Schema::dropIfExists('ticket_pipeline');
        // Schema::dropIfExists('lead_pipeline');
        // Schema::dropIfExists('task_pipeline');
        Schema::dropIfExists('deal_pipeline');
    }
};
