<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHeader extends Model
{
    use HasFactory;

    protected $table = 'stock_headers';

    protected $fillable = [
        'supplier_name',
        'delivery_receipt',
        'order_date',
        'delivery_date',
        'remarks',
        'encoded_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
