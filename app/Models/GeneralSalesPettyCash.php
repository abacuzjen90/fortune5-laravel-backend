<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSalesPettyCash extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'denomination',
        'quantity',
        'amount',
        'encoder',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
