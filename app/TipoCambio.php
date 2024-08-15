<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoCambio extends Model
{

    protected $table = 'tipo_cambios'; // Especifica la tabla si no sigue la convención de nombre

    // Define los campos que se pueden llenar mediante asignación masiva
    protected $fillable = [
        'fecha',
        'tipo_cambio_mxn',
        'tipo_cambio_usd',
        'tipo_conversion', 
        'id'
    ];

    // Si deseas que la fecha se trate como un objeto de Carbon en lugar de una cadena
    protected $casts = [
        'fecha' => 'date',
    ];
}
