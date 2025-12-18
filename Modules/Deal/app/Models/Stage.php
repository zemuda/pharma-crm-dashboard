<?php

namespace Modules\Deal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Deal\Database\Factories\StageFactory;

class Stage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): StageFactory
    // {
    //     // return StageFactory::new();
    // }
}
