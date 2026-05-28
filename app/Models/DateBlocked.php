<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DateBlocked extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'encoder',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
