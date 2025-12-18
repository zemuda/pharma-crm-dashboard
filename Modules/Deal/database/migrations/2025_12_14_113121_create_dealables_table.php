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
        Schema::create('dealables', function (Blueprint $table) {
            $table->id(); // Auto-increment ID
            $table->foreignId('deal_id')->constrained('deals')->cascadeOnDelete();
            $table->morphs('dealable');
            $table->timestamps(); // Track when created/updated
            $table->unique(['deal_id', 'dealable_id', 'dealable_type']); // Prevent duplicates
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealables');
    }
};
