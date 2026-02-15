<?php

namespace Modules\Activities\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Activities\Database\Factories\ActivityTypesFactory;

class ActivityType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): ActivityTypesFactory
    // {
    //     // return ActivityTypesFactory::new();
    // }
}
