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
            $table->unsignedBigInteger('parent_id')->nullable(); // For hierarchical structure
            $table->string('name');
            $table->string('code')->unique(); // e.g., "A02BC02"
            $table->tinyInteger('level'); // 1 (Anatomical) to 5 (Chemical)
            $table->string('slug')->unique(); // Unique slug for URL usage
            $table->string('info')->nullable();
            $table->string('status')->default(ActiveStatus::Active->value);
            $table->timestamps();
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
