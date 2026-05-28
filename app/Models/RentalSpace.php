<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalSpace extends Model
{
    use HasFactory;

    protected $fillable = [
        'desc',
        'property_name',
        'unit_number',
        'type',
        'address',
        'monthly_rent',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
