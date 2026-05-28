<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory_Unit_Big extends Model
{
    use HasFactory;

    protected $table = 'inventory_unit_big';

    protected $fillable = [
        'big_unit',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
