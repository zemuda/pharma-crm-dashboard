<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Enums\ActiveStatus;
use Modules\Core\Enums\PriorityEnum;
use Modules\Core\Enums\RecurrenceTypeEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();

            $table->text('description')->nullable();
            $table->text('note')->nullable();

            $table->date('due_date')->index();
            $table->time('due_time')->nullable()->index();

            $table->time('end_time')->nullable()->index();
            $table->date('end_date')->index();

            $table->unsignedInteger('reminder_minutes_before')->nullable();
            $table->dateTime('reminder_at')->index()->nullable();
            $table->dateTime('reminded_at')->nullable();

            $table->foreignId('user_id')->comment('Owner')->constrained('users');

            $table->dateTime('owner_assigned_date');

            $table->foreignId('activity_type_id')->constrained('activity_types');

            $table->dateTime('completed_at')->index()->nullable();

            $table->foreignId('created_by')->constrained('users');
            $table->index(['due_date', 'due_time']);

            // Recurring settings
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurring_type', ['daily', 'weekly', 'monthly', 'yearly'])->nullable();
            $table->unsignedInteger('recurring_interval')->default(1); // Every n days/weeks/months/years
            $table->json('recurring_days_of_week')->nullable(); // For weekly recurrence: e.g., [1,3,5] for Mon, Wed, Fri
            $table->unsignedInteger('recurring_day_of_month')->nullable(); // For monthly recurrence
            $table->unsignedInteger('recurring_month_of_year')->nullable(); // For yearly recurrence
            $table->date('recurring_end_date')->nullable(); // When recurrence ends
            $table->unsignedInteger('recurring_occurrences')->nullable(); // Number of occurrences  

            // Enum-backed columns
            $table->string('status')->default(ActiveStatus::Active->value);
            $table->string('priority')->default(PriorityEnum::Medium->value);
            $table->string('recurrence_type')->default(RecurrenceTypeEnum::None->value);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
