<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = "pagos";

    protected $fillable = [
        'factura_id',
        'monto',
        'referencia',
        'fecha_pago',
        'complemento',
        'lote_pago_id',
        'batch_id',
        'serial_baunche',
        'num_cuenta',
        'banco_prov', // Asegúrate de que este campo esté presente si lo necesitas
    ];

    // Relación con Factura
    public function factura()
    {
        return $this->belongsTo(Factura::class, 'factura_id'); // Cambia hasMany a belongsTo
    }

    // Relación con Customer
    public function cliente()
    {
        return $this->belongsTo(Customer::class, 'cliente_id'); // Asegúrate de tener 'cliente_id' en pagos
    }

    // Relación con LotePago
    public function lotePago()
    {
        return $this->belongsTo(LotePago::class, 'lote_pago_id');
    }

    public function clienteBanco()
    {
    return $this->belongsTo(ClienteBanco::class, 'cliente_id');
    }

    
}
