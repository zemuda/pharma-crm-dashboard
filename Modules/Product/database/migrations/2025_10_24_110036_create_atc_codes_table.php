<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Enums\ActiveStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('atc_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->string('code')->unique();
            $table->tinyInteger('level'); // 1-5
            $table->string('slug')->unique();
            $table->text('info')->nullable();
            $table->string('status')->default(ActiveStatus::Active->value);

            // For adjacency list performance
            $table->unsignedBigInteger('_lft')->nullable()->index();
            $table->unsignedBigInteger('_rgt')->nullable()->index();

            // Additional metadata
            $table->string('who_guidelines')->nullable();
            $table->text('therapeutic_uses')->nullable();
            $table->text('contraindications')->nullable();
            $table->json('meta')->nullable();
            $table->integer('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['parent_id', 'level']);
            $table->index('level');
            $table->index(['status', 'level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atc_codes');
    }
};
