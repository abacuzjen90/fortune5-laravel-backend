<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryBooklet extends Model
{
    use HasFactory;

    protected $table = 'sys_inventory_form';

    protected $fillable = [
        'form_name',
        'form_description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
