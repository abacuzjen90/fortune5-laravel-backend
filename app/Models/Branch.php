<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'str_list';

    protected $fillable = [
        'str_list_id',
        'head_person',
        'branchtype_id',
        'branchtype',
        'acronym',
        'description',
        'str_list_address',
        'contact_number',
        'per_cbm',
        'per_kilo',
        'val_charge',
        'fcl_value_charge',
        'min_charge',
        'advalorem',
        'ftr10',
        'ftr20',
        'ftr40',
        'wheeler4',
        'wheeler6',
        'wheeler8',
        'wheeler10',
        'freightliner',
        'rolling_cargo',
        'ftr10_value',
        'ftr20_value',
        'ftr40_value',
        'wheeler4_value',
        'wheeler6_value',
        'wheeler8_value',
        'wheeler10_value',
        'freightliner_value',
        'rolling_cargo_value',
        'airvalue',
        'management_fee',
        'agency_10ftr',
        'agency_20ftr',
        'agency_40ftr',
        'small_rate',
        'medium_rate',
        'large_rate',
        'parcel_rate',
        'status',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
