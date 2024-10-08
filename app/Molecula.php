<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Molecula extends Model
{
    protected $table = 'molecula1';

    protected $fillable = [
        "id",
        "bol_number",
        "litros",
        "rate",
        "total",
        "created_at",
        "updated_at",
        "estatus",
        "NumeroFactura",
        "customer_name"
    ];

    
}