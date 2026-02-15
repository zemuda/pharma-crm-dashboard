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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Kenya
            $table->string('code', 3)->unique()->nullable(); // KE
            $table->string('phone_code', 5)->nullable(); // +254
            $table->string('currency', 3)->nullable(); // KES
            $table->timestamps();
        });
        
        Schema::create('counties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 3)->unique(); // e.g., "047"
            $table->timestamps();

            // Index for fast lookup in dropdowns
            $table->index(['country_id', 'name']);
        });

        Schema::create('sub_counties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('county_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();

            // Prevent duplicate sub-counties in the same county
            $table->unique(['county_id', 'name']);
        });

        Schema::create('wards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_county_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();

            $table->index(['sub_county_id', 'name']);
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable(); // e.g., "Mombasa Warehouse"
            
            // Physical Details
            $table->string('street_address')->nullable();
            $table->string('building_name')->nullable();
            $table->string('floor_no')->nullable();
            $table->string('house_no')->nullable();
            
            // Relationships (Cascading references)

            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('county_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sub_county_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('ward_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('town_id')->nullable()->constrained()->nullOnDelete();


            $table->string('postal_code')->nullable();
            $table->text('directions')->nullable();
        
            // Coordinates for the "Receiving Area" Map
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            $table->timestamps();

            // Performance Index for searching leads by location
            $table->index(['county_id', 'sub_county_id', 'ward_id', 'town_id'], 'location_index');
        });

        Schema::create('towns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('county_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sub_county_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('ward_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('postal_code', 10)->nullable(); // e.g., 00100
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();

            $table->index(['county_id', 'sub_county_id', 'ward_id', 'name']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
        Schema::dropIfExists('counties');
        Schema::dropIfExists('sub_counties');
        Schema::dropIfExists('wards');
        Schema::dropIfExists('towns');
        Schema::dropIfExists('addresses');
    }
};
