<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Molecula3 extends Model 
{

    protected $table = 'molecula3';

    protected $fillable = [
        'id',
        'bol_id',
        'precio_molecula1',
        'precio_molecula3',
        'resultado',
        'created_at',
        'updated_at',
        'transportation_fee',
        'weight_controller',
        'total',
        'status',
        'NumeroFactura',
        'customer_name'

    ];

}