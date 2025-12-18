<?php

namespace Modules\Deal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Deal\Database\Factories\DealFactory;

class Deal extends Model
{
    use HasFactory;

    // The trait will automatically use 'deal' as type name
    // from the table name 'deals'
    
    // If you need to override the type name:
    // protected function getPipelineTypeName()
    // {
    //     return 'deal'; // Explicitly return 'deal'
    // }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): DealFactory
    // {
    //     // return DealFactory::new();
    // }
}
