<?php

namespace Modules\Activities\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Enums\ActiveStatus;
use Modules\Core\Enums\PriorityEnum;
use Modules\Core\Enums\RecurrenceTypeEnum;

// use Modules\Activities\Database\Factories\ActivitiesFactory;

class activities extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected $casts = [
        'status' => ActiveStatus::class,
        'priority' => PriorityEnum::class,
        'recurrence_type' => RecurrenceTypeEnum::class,
        'recurrence_days' => 'array',
        'recurrence_interval' => 'integer',
        'start_at' => 'datetime',
        'due_at' => 'datetime',
    ];

    // protected static function newFactory(): ActivitiesFactory
    // {
    //     // return ActivitiesFactory::new();
    // }
}
