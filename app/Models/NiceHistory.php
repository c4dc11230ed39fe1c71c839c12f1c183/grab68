<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NiceHistory extends Model
{
    use HasFactory;

    protected $table = 'nice_history';

    protected $fillable = [
        'sjc',
        'nice',
    ];

    public static function addHistory($sjc, $nice)
    {
        $lastEntry = self::orderBy('created_at', 'desc')->first();

        if ($lastEntry && ($lastEntry->created_at->gt(now()->subMinute()) || ($lastEntry->sjc == $sjc && $lastEntry->nice == $nice))) {
            // If the last entry was created less than 1 minute ago or the sjc and nice values haven't changed, don't create a new entry
            return $lastEntry;
        }

        return self::create([
            'sjc' => $sjc,
            'nice' => $nice
        ]);
    }
}
