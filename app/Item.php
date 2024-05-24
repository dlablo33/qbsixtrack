<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    protected $table = 'app_detalleprueba';

    protected $fillable = [
        'id',
        'NumeroFactura',
        'bol',
        'Trailer',
        'Cliente',
        'meta_data',
        'create_time',
        'last_updated_time',
        'item_names',
        'item_account_name',
        'item_ref_value',
        'unit_price',
        'quantity',
        'item_account_ref_value',
        'tax_code_ref_value',
        'total_amt',
        'currency_value',
        'currency_name',
        'customer_value',
        'customer_name',
        'bill_line2',
        'direccion',
        
    ];

    public function items()
    {
        return $this->hasMany(Item::class); // Assuming 'Item' is the related model
    }

}
