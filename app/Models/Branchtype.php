<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branchtype extends Model
{
    use HasFactory;

    protected $table = 'str_branchtype';

    protected $fillable = [
        'type',
        'description',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
