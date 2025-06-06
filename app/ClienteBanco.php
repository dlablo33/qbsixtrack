<?php

namespace App;

use App\Tarifa;
use Illuminate\Database\Eloquent\Model;

class ClienteBanco extends Model
{
    protected $table = "cliente_banco";
    protected $fillable = 
    [ 
    "id",
    "cliente_id",
    "banco_id",
    "saldo_mxn",
    "saldo_usd",
    "created_at",
    "updated_at",
    "asignado"
    ];

    public function banco()
    {
        return $this->belongsTo(Banco::class, 'banco_id');
    }

    public function historial()
    {
        return $this->hasMany(Historial::class);
    }

    public function cliente()
    {
        return $this->belongsTo(CustomerAdmin::class, 'cliente_id');
    }
    
    public function pagos()
    {
    return $this->hasMany(Pago::class);
    }

}