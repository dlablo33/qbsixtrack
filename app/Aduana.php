<?php

namespace App;

use App\Logistica;
use Illuminate\Database\Eloquent\Model;

class Aduana extends Model
{
    protected $table = "aduanas";
    
        // Los atributos que se pueden asignar de manera masiva
        protected $fillable = [
            'pedimento',
            'linea',
            'no_pipa',
            'bol_number',
            'precio',
            'honorario',
            'dls',
            'status'
        ];
    
        // Si usas timestamps, estos son los nombres de las columnas de las fechas en tu base de datos
        public $timestamps = true;
    
        public function logistica()
        {
            return $this->belongsTo(Logistica::class);
        }

        // En el modelo Aduana.php
public function agenteAduanal()
{
    return $this->belongsTo(AgenteAduanal::class, 'id');
}

}