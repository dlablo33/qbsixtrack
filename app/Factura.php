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
        'updated_at',
        'code_factura',
        'estatus',
        'pedimento',
        'precio'
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

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'cliente_id');
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class, 'lote_pago_id', 'id');
        // Asegúrate de usar los nombres correctos de la clave foránea y la clave local
    }

}
