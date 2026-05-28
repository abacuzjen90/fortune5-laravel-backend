<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalExpenses extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'property',
        'category',
        'amount',
        'notes',
        'encoder',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
