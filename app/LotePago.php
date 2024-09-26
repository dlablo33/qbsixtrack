<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LotePago extends Model
{
    protected $table = 'lotes_pagos'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'fecha',
        'cliente_id',
        'total_pago',
        'complemento',
    ];

    // Relación con Pago
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'lote_pago_id'); // Un lote puede tener muchos pagos
    }

    // Si quieres acceder a la información del cliente, podrías agregar esta relación
    public function cliente()
    {
        return $this->belongsTo(Customer::class, 'cliente_id'); // Asegúrate de que exista este campo en la tabla
    }
    public function facturas()
    {
        return $this->hasMany(Factura::class, 'lote_pago_id', 'id');
        // Asegúrate de usar los nombres correctos de la clave foránea y la clave local
    }
}

