<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $table = 'str_destination';

    protected $fillable = [
        'destination',
        'rate_cbm',
        'rate_kilo',
        'value_charge',
        'minimum',
        'advalorem',
        'encoder',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
