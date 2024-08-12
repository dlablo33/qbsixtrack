<?php

namespace App;

use App\Tarifa;
use Illuminate\Database\Eloquent\Model;

class Destino extends Model
{
    protected $table = "destinos";
    protected $fillable = 
    [ 
    "id",    
    "nombre",
    ];

    public function tarifas()
    {
        return $this->hasMany(Tarifa::class);
    }

    public function moleculas()
    {
        return $this->hasMany(Molecula2::class, 'destino_id');
    }
    
}