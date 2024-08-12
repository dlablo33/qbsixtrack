<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    protected $table = 'gastos';

    protected $fillable = [
        'fecha',
        'clasificacion',
        'beneficiario',
        'descripcion',
        'cantidad',
        'banco_id',
        'moneda',
        'created_at',
        'updated_at'
    ];
}
