<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangePair extends Model
{
    use HasFactory;

    protected $table = 'exchange_pairs';

    protected $fillable = [
        'name',
        'base',
        'quote',
        'type',
    ];

    public static function addPair($name, $base, $quote)
    {
        return self::firstOrCreate(
            ['name' => $name, 'base' => $base, 'quote' => $quote, 'type' => 'market'],
            ['name' => $name, 'base' => $base, 'quote' => $quote, 'type' => 'market']
        );
    }
}
