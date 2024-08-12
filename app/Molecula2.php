<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Molecula2 extends Model
{
    protected $table = 'molecula2';

    protected $fillable = [
        'bol', 'order_number', 'semana', 'fecha', 'linea', 'no_pipa', 
        'cliente', 'destino', 'transportista_id', 'destino_id', 'status',
        'cruce', 'litros', 'precio', 'fecha_salida', 'fecha_entrega', 
        'fecha_descarga', 'pedimento'
    ];

    public function destino()
    {
        return $this->belongsTo(Destino::class, 'destino_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Customer::class, 'cliente_id');
    }

    
}

