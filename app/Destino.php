<?php

namespace App;

use App\Tarifa;
use Illuminate\Database\Eloquent\Model;

class Destino   extends Model
{
    protected $table = "destinos";
    protected $fillable = 
    [ 
    "nombre",
    ];

    public function tarifas()
    {
        return $this->hasMany(Tarifa::class);
    }
}