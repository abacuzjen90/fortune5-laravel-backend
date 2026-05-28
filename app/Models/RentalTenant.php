<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalTenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'contact_number',
        'email_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
