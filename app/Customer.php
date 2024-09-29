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

    public function facturas()
    {
        return $this->hasMany(Factura::class, 'cliente_id');
    }

    public function moleculas()
    {
        return $this->hasMany(Molecula2::class, 'cliente_id');
    }
    
    // En el modelo Customer o Cliente
public function depositos()
{
    return $this->hasMany(ClienteBanco::class, 'cliente_id');
}

}
