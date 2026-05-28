<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;

    protected $table = 'emp_designation';

    protected $fillable = [
        'designation',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
