<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Product\Database\Factories\MedicineAtcCodeFactory;

class MedicineAtcCode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): MedicineAtcCodeFactory
    // {
    //     // return MedicineAtcCodeFactory::new();
    // }
}
