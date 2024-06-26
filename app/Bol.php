<?php

namespace App;

use App\Tarifa;
use App\Marchant;
use Illuminate\Database\Eloquent\Model;

class Bol   extends Model
{
    protected $table = "bol_details";
    protected $fillable = 
    [ 
    "id",
    "numero_bol",
    "traile",
    "factura_id_1",
    "factura_id_2",
    "factura_id_3",
    "concepto",
    "total_factura_1",
    "total_factura_2",
    "total_factura_3",
    "cliente_id",
    "transporte_id",
    "total_transporte",
    "total_final",
    "created_at",
    "updated_at",
    ];

    public function cliente()
    {
        return $this->belongsTo(Customer::class);
    }

    public function transporte()
    {
        return $this->belongsTo(Tarifa::class);
    }


}