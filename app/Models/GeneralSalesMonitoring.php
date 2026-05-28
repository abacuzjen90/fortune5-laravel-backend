<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSalesMonitoring extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'type',
        'referenceno',
        'name',
        'description',
        'mode_of_payment',
        'gcash_referenceno',
        'bank',
        'bank_date',
        'checkno',
        'receiving_bank',
        'bank_transfer_refno',
        'amount',
        'encoder',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
