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
        Schema::create('pipeline_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pipeline_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('color')->nullable()->default('#3b82f6');
            $table->integer('order')->default(0);
            $table->decimal('probability', 5, 2)->default(0)->nullable();
            $table->boolean('is_default')->default(false);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['pipeline_id', 'order']);
            $table->index(['pipeline_id', 'is_default']);
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pipeline_stages');
    }
};
