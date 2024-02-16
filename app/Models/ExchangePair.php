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
        'description',
    ];

    public static function addPair($name, $base, $quote, $type, $description = null)
    {
        return self::firstOrCreate(
            ['name' => $name, 'base' => $base, 'quote' => $quote, 'type' => $type],
            ['name' => $name, 'base' => $base, 'quote' => $quote, 'type' => $type, 'description' => $description]
        );
    }
}
