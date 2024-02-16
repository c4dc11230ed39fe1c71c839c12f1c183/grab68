<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatesHistory extends Model
{
    use HasFactory;

    protected $table = 'rates_history';

    protected $fillable = [
        'exchange_pair_id',
        'buy',
        'sell',
    ];

    public static function addRate($exchangePairId, $buyRate, $sellRate)
    {
        return self::create([
            'exchange_pair_id' => $exchangePairId,
            'buy' => $buyRate,
            'sell' => $sellRate
        ]);
    }
}
