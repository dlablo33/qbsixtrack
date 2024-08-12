<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmpresaCuenta extends Model
{
    protected $table = 'empresa_cuenta';

    protected $fillable = [
        'banco',
        'ingreso_mxn',
        'ingreso_usd',
        'comision_mxn',
        'comision_usd',
        'saldo_final_mxn',
        'saldo_final_usd'
    ];
}
