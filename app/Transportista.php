<?php

namespace App;

use App\Tarifa;
use Illuminate\Database\Eloquent\Model;

class Transportista  extends Model
{
    protected $table = "transportistas";
    protected $fillable = 
    [ 
    "nombre",
    ];

    public function tarifas()
    {
        return $this->hasMany(Tarifa::class);
    }
}