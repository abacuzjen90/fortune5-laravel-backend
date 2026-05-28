<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aircharge extends Model
{
    use HasFactory;

    protected $table = 'sys_aircharge';

    protected $fillable = [
        'type',
        'consignee',
        'wtbreak',
        'express',
        'perishable',
        'gen_cargo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
