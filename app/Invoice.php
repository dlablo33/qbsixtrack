<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Invoice extends Model
{

    protected $table = 'app_Muestra';

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
        'total_amt',
        'currency_value',
        'customer_name',
        'bill_line2',
        'estatus',

    ];    

        public function items()
        {
            return $this->belongsToMany(Item::class, 'app_Item'); // Assuming a pivot table named 'invoice_item'
        }

        public function client()
        {
            return $this->belongsTo(Marchant::class, 'id'); // Asegúrate de que 'client_id' es la clave foránea correcta.
        }
    }
