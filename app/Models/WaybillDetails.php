<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaybillDetails extends Model
{
    use HasFactory;

    protected $table = 'sys_waybilldetails';


     protected $primaryKey = 'sys_wbdetailsid';

    public $timestamps = false;

    protected $fillable = [
        "sys_wbdetailsid",
        "type",
        "chargeto_id",
        "waybillno",
        "wb_description",
        "unit",
        "quantity",
        "confirmqty",
        "remaining_qty",
        "variance",
        "weight",
        "declared_value",
        "declared_value2",
        "value_charge",
        "total",
        "rates",
        "length",
        "width",
        "height",
        "kilos_or_cbm",
        "total_kls",
        "customer_rates",
        "customer_cbm",
        "customer_kilo",
        "freight_charge",
        "cus_specialitem_id",
        "cus_specialitem_remarks",
        "line_cv",
        "line_fc",
        "total_freight_charge",
        "cbm_uniq_id",
        "delivery_id",
        "delivery_status",
        "posting_status",
        "created_date",
        "modified_date",
        "date_created",
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
