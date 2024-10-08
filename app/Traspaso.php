<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traspaso extends Model
{

    protected $fillable = [
        'banco_origen',
        'banco_destino',
        'cantidad',
        'moneda',
    ];

    public function bancoOrigen()
    {
        return $this->belongsTo(EmpresaCuenta::class, 'banco_origen');
    }

    public function bancoDestino()
    {
        return $this->belongsTo(EmpresaCuenta::class, 'banco_destino');
    }
}
