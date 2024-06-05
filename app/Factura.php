<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Factura extends Model
{
    protected $table = 'fac_invoice';

    protected $fillable = [
        'id',
        'cliente_id',
        'cliente_name',
        'producto_name',
        'producto_id',
        'fecha_create',
        'due_fecha',
        'cantidad',
        'bol',
        'trailer',
        'Numero_Factura',
        'total',
        'created_at',
        'update_at',

        
    ];

    public function products()
    {
        return $this->hasMany(Product::class); // Assuming 'Item' is the related model
    }

    public function customers()
    {
        return $this->hasMany(Customer::class); // Assuming 'Item' is the related model
    }

    public function precios()
    {
        return $this->hasMany(Precio::class); // Assuming 'Item' is the related model
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function montoPendiente()
    {
        $pagosTotales = $this->pagos->sum('monto');
        return $this->total - $pagosTotales;
    }

}
