<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerAdmin extends Model
{
    protected $table = "customer_admin";
    protected $fillable = 
    [ 
    "id",
    "cliente",
    "mxn",
    "usd",
    "created_at",
    "updated_at"
    
    ];

    public function bancos()
    {
        return $this->belongsToMany(Banco::class, 'cliente_banco', 'cliente_id', 'banco_id')
                    ->withPivot('saldo_mxn', 'saldo_usd');
    }
}