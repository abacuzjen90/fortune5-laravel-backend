<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockDetails extends Model
{
    use HasFactory;

    protected $table = 'stock_details';

   protected $fillable = [
        'header_id',
        'product_id',
        'sku',
        'unit',
        'quantity',
        'issued_qty',
        'remaining_qty',
        'price_per_unit',
        'cost_per_unit',
        'discount',
        'total_purchase_cost',
        'common_name',
        'big_unit',
        'big_qty',
        'big_price',
        'big_cost',
        'big_conversion',
        'small_conversion',
    ];

    /**
     * Relationship: StockDetail belongs to a StockHeader
     */
    public function stockHeader()
    {
        return $this->belongsTo(StockHeader::class, 'header_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
