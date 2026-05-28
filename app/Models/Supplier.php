<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'str_supplier';

    protected $fillable = [
        'supplier_name',
        'address',
        'contact_details',
        'contact_person',
        'terms',
        'tin',
        'tax',
        'emailaddress',
    ];
}
