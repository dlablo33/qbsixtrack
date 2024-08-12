<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logistica extends Model
{
    protected $table = 'logistica';

    protected $fillable = [
        'bol',
        'order_number',
        'semana',
        'fecha',
        'linea',
        'no_pipa',
        'cliente',
        'destino',
        'status',
        'transportista_id',
        'destino_id',
        'cruce',
        'litros',
        'precio',
        'fecha_salida',
        'fecha_entrega',
        'fecha_descarga',
        'pedimento'
        
    ];

    protected $casts = [
        'fecha' => 'date',
    ];


    public function showForm()
    {
    $clientes = Customer::all(); // Obtiene todos los clientes
    return view('logistica.index', compact('clientes'));
    }

    public function transportista()
    {
        return $this->belongsTo(Transportista::class);
    }

    public function destino()
    {
        return $this->belongsTo(Destino::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Customer::class, 'cliente', 'id');
    }

}
