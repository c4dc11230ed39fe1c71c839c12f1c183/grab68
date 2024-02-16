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
        $lastEntry = self::where('exchange_pair_id', $exchangePairId)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastEntry && $lastEntry->created_at->gt(now()->subMinute()) && $lastEntry->buy == $buyRate && $lastEntry->sell == $sellRate) {
            // If the last similar entry was created less than 1 minute ago and the buy and sell rates haven't changed, don't create a new entry
            return $lastEntry;
        }

        return self::create([
            'exchange_pair_id' => $exchangePairId,
            'buy' => $buyRate,
            'sell' => $sellRate
        ]);
    }
}
