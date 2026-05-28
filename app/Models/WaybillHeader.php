<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaybillHeader extends Model
{
    use HasFactory;

    protected $table = 'sys_waybillheader';

    public $timestamps = false;

    protected $fillable = [
        "waybillno",
        "hwaybillnumber",
        "crs_number",
        "modeoftransaction",
        "waybilldate",
        "charge_to",
        "consignee",
        "address",
        "destination_from",
        "destination_to",
        "location",
        "terms",
        "customer_minimum",
        "shipper",
        "type",
        "agency_type",
        "encoder",
        "encoded",
        "time",
        "checker",
        "appraiser",
        "delivered_by",
        "pickupby",
        "typist_name",
        "memo",
        "memo_encoder",
        "branch",
        "total_quantity",
        "amount",
        "status",
        "shipper_own_risk",
        "wb_missing_status",
        "delivery",
        "blocklist",
        "posting_date",
        "posting_time",
        "posting_user",
        "cancel_cost",
        "cancel_remark",
        "cancel_encoder",
        "search_status",
        "receivable_id",
        "proof_of_delivery",
        "transfer_status",
        "update_user",
        "update_date",
        "retrived_status",
        "ptf_status",
        "ptf_date",
        "food",
        "liquid",
        "breakable",
        "glass",
        "cm_lookup_remarks",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
