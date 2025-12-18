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
        Schema::create('pipeline_type_associations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pipeline_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('pipeline_type_id')
                ->constrained()
                ->onDelete('cascade');
            $table->boolean('is_default')->default(false);
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->unique(['pipeline_id', 'pipeline_type_id']);
            $table->index(['pipeline_type_id', 'is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pipeline_type_associations');
    }
};
