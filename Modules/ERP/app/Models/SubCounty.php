<?php

namespace Modules\ERP\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\ERP\Database\Factories\SubCountyFactory;

class SubCounty extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): SubCountyFactory
    // {
    //     // return SubCountyFactory::new();
    // }
}
