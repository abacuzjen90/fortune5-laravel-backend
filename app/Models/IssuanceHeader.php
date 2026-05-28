<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssuanceHeader extends Model
{
    protected $table = 'issuance_headers';

    protected $fillable = [
        'drletter',
        'drno',
        'customer_name',
        'address',
        'contact_number',
        'total_quantity',
        'total_amount',
        'transaction_date',
        'terms',
        'encoded_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    use HasFactory;
}
