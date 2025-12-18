<?php

namespace Modules\Deal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Deal\Database\Factories\DealStageHistoryFactory;

class DealStageHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): DealStageHistoryFactory
    // {
    //     // return DealStageHistoryFactory::new();
    // }
}
