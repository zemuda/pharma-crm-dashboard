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
        Schema::create('pipeline_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->string('model_class')->nullable();
            $table->string('table_name')->nullable();
            $table->string('foreign_key')->nullable();
            $table->string('route_key')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('has_probability')->default(false);
            $table->boolean('has_value')->default(false);
            $table->boolean('has_due_date')->default(false);
            $table->boolean('has_priority')->default(false);
            $table->json('custom_fields')->nullable();
            $table->json('validations')->nullable();
            $table->json('default_stages')->nullable();
            $table->integer('order')->default(0);
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'order']);
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pipeline_types');
    }
};
