<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $table = 'inventory_items';

    protected $fillable = [
        'product_name',
        'sku',
        'cost_per_unit',
        'reorder_level',
        'image',
        'encoded_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
