<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgenteAduanal extends Model
{

    protected $table = 'agentes_aduanales'; // Especifica el nombre de la tabla

    protected $fillable = ['nombre', 'codigo', 'rfc', 'telefono', 'email'];
}
