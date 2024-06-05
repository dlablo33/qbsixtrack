<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = "customers";
    protected $fillable = [
    'id',
    'CLIENTE_LP',
    'NOMBRE_COMERCIAL',
    'STATUS',
    'RFC',
    'RAZON_SOCIAL',
    'EMPRESA_VENDEDORA',
    'CODIGO_CUENTA_CONTABLE',
    'CODIGO_CLIENTE_COMERCIAL',
    'DENOMINACION_SERIAL',
    'CVE_CTE',
    'email',
    'saldo_a_favor'

    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'cliente_id');
    }

}
