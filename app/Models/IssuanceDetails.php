<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssuanceDetails extends Model
{
    use HasFactory;

    protected $table = 'issuance_details';

   protected $fillable = [
        'issuance_id',
        'stock_id',
        'product_id',
        'unit',
        'quantity',
        'unit_price',
        'amount',
        'discount',
        'status',
        'unit_type',
    ];


    public function IssuanceHeader()
    {
        return $this->belongsTo(IssuanceHeader::class, 'issuance_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
