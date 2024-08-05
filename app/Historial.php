<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    protected $table = 'historials';
    protected $fillable = ['cliente_banco_id', 'accion', 'cantidad', 'saldo_anterior'];

    public function clienteBanco()
    {
        return $this->belongsTo(ClienteBanco::class, 'cliente_banco_id');
    }
}
