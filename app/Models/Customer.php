<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'str_customer';

    protected $fillable = [
        'cust_uniq_id',
        'registered_name',
        'charge_to',
        'tin_number',
        'contact_person',
        'address',
        'mobile_number',
        'contact_number',
        'branch_id',
        'destination',
        'value_charge',
        'terms',
        'rate_cbm',
        'rate_kilo',
        'airvalue',
        'minimum',
        'advalorem',
        'discount',
        'small_rate',
        'medium_rate',
        'large_rate',
        'parcel_rate',
        'account_type',
        'agency_type',
        'vat',
        'applicable_tax',
        'fcl_value_charge',
        'ftr10',
        'ftr20',
        'ftr40',
        'ftr20_flat',
        'ftr40_flat',
        'wheeler4',
        'wheeler6',
        'wheeler8',
        'wheeler10',
        'freightliner',
        'rolling_cargo',
        'ftr10_value',
        'ftr20_value',
        'ftr40_value',
        'ftr20_flat_value',
        'ftr40_flat_value',
        'wheeler4_value',
        'wheeler6_value',
        'wheeler8_value',
        'wheeler10_value',
        'freightliner_value',
        'rolling_cargo_value',
        'reason',
        'pickup_charge_remarks',
        'customer_dr_attachment',
        'rates_to_apply',
        'disabled_encoder',
        'date_disabled',
        'status',
        'blacklist_status',
        'date_blacklisted',
        'old_status',
        'verify',
        'rate_status',
        'rate_status_time',
        'rate_status_date',
        'rate_status_encoder',
        'blocklist',
        'encoded',
        'encoder',
        'user_updated',
        'deactive_by',
        'blacklisted_by',
        'update_rate_user',
        'update_rate_time_date',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
