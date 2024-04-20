<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Invoice extends Model
{
    protected $table = "app_invoice_qb";
    protected $fillable = [
        "id",
        "NumeroFactura",
        "bol",
        "Trailer",
        "Cliente",
        "meta_data",
        "create_time",
        "last_updated_time",
        "item_names",
        "item_account_name",
        "item_ref_value",
        "unit_price",
        "quantity",
        "item_account_ref_value",
        "tax_code_ref_value",
        "total_amt",
        "currency_value",
        "currency_name",
        "customer_value",
        "customer_name",
        "bill_line2"
        
    ];

    public function getFormattedPrice()
    {
        return number_format($this->unit_price, 2);
    }

    public function getTotalPrice()
    {
        return $this->quantity * $this->unit_price;
    }
      // Accessor method to handle different custom field storage formats (optional)
      public function getCustomFieldsAttribute()
      {
          $data = $this->custom_field;

          return $data;
      }
  
      // Alternative approach using raw query to fetch invoices with custom fields (optional)
      public static function getInvoicesWithCustomFields()
      {
          return DB::table('invoice')
              ->select([
                  'invoice.*',
                  DB::raw('JSON_DECODE(custom_field) AS custom_fields'), // Assuming JSON storage
              ])
              ->get();
      }
}
