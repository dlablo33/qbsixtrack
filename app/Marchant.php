<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marchant extends Model 
{
    protected $table = "precios";

    protected $fillable =    [ 
        "id",
        "cliente_id",
        "producto_id",
        "cliente_name",
        "producto_name",
        "precio",
        "created_at",
        "updated_at",
        "semana",
        "fecha_vigencia",
        ];

}
