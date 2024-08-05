<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    protected $table = "devoluciones";

    protected $fillable = [
        'id_deposito',
        'cliente_id',
        'banco_id',
        'cantidad',
        'moneda'
    ];

    public function cliente()
    {
        return $this->belongsTo(CustomerAdmin::class, 'cliente_id');
    }

    public function banco()
    {
        return $this->belongsTo(Banco::class);
    }
}
