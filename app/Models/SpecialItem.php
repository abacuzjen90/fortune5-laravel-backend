<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialItem extends Model
{
    use HasFactory;

    protected $table = 'cus_special';

    protected $fillable = [
        'customer_id',
        'consignee_id',
        'special_item',
        'rate_php',
        'unit',
        'length',
        'width',
        'height',
        'cbm',
        'kilo',
        'value_charge',
        'account_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
