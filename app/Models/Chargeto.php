<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chargeto extends Model
{
    use HasFactory;

    protected $table = 'str_chargeto';

    protected $fillable = [
        'payer_name',
        'branch',
        'address',
        'mobile_number',
        'contact_person',
        'remarks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
