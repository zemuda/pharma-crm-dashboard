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
        Schema::create('formulations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained('medicines')->onDelete('cascade');
            $table->foreignId('dose_form_id')->constrained('dose_forms')->onDelete('set null')->nullable();
            $table->foreignId('route_of_administration_id')->constrained('route_of_administrations')->onDelete('set null')->nullable();
            $table->unsignedTinyInteger('level_of_use')->nullable();

            //  Concentration details
            $table->decimal('concentration_amount', 8, 3); // e.g., 1 (for 1mg)
            $table->string('concentration_unit'); // e.g., "mg"            
            $table->decimal('volume_amount', 8, 3)->nullable(); // e.g., 1 (for 1ml)
            $table->string('volume_unit')->nullable(); // e.g., "ml"

            // Additional chemical form (for salts like sulphate)
            $table->string('chemical_form')->nullable(); // e.g., "sulphate"

            $table->string('info')->nullable(); // Additional information about the strength
            $table->text('description')->nullable(); // Optional description field
            $table->string('status')->default(ActiveStatus::Active->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formulations');
    }
};
