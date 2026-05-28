<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $table = 'sys_delivery';


    public $timestamps = false;

    protected $fillable = [
        "branch",
        "goingto",
        "transhipment",
        "waybilldate",
        "confirmdate",
        "waybillid",
        "waybillno",
        "hwaybillnumber",
        "crs_number",
        "consignee",
        "shipper",
        "item_id",
        "cargoid",
        "cargo_details_id",
        "item_cbm_id",
        "unit",
        "rates",
        "declared_value",
        "freight_charge",
        "item_description",
        "item_quantity",
        "remaining_qty",
        "loaded",
        "isexceed",
        "exceedqty",
        "datechange",
        "reason",
        "stocksinwb",
        "isvoyage",
        "onsave",
        "misrouted",
        "agency_status",
        "posting_date",
        "date_created",
        "retrived_status",
        "disposal_remarks",
        "disposal_user",
        "disposal_date",
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
