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
        Schema::create('dose_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->comment('Dose Form Type')->nullable(); // e.g., oral solid, liquid, injectable
            $table->string('name');           // e.g., Tablet, Injection
            $table->string('code')->unique()->nullable(); // e.g., TAB, INJ
            $table->text('description')->nullable();
            $table->string('status')->default(ActiveStatus::Active->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dose_forms');
    }
};
