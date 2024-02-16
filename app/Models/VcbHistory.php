<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VcbHistory extends Model
{
    use HasFactory;

    protected $table = 'vcb_history';

    protected $fillable = [
        'currency',
        'buy_tm',
        'buy_ck',
        'sell_tm',
        'sell_ck',
    ];

    public static function addHistory($currency, $buyTm, $buyCk, $sellTm, $sellCk)
    {
        $lastEntry = self::where('currency', $currency)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastEntry && ($lastEntry->created_at->gt(now()->subMinute()) || ($lastEntry->buy_tm == $buyTm && $lastEntry->buy_ck == $buyCk && $lastEntry->sell_tm == $sellTm && $lastEntry->sell_ck == $sellCk))) {
            // If the last similar entry was created less than 1 minute ago or the buy and sell rates haven't changed, don't create a new entry
            return $lastEntry;
        }

        return self::create([
            'currency' => $currency,
            'buy_tm' => $buyTm,
            'buy_ck' => $buyCk,
            'sell_tm' => $sellTm,
            'sell_ck' => $sellCk
        ]);
    }
}
