<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory_Unit_Small extends Model
{
    use HasFactory;

    protected $table = 'inventory_unit_small';

    protected $fillable = [
        'small_unit',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
