<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    protected $table = "bancos";
    protected $fillable = 
    [ 
    "id",
    "banco",
    "created_at",
    "updated_at",
    ];
    
    public function depositos()
    {
        return $this->hasMany(ClienteBanco::class, 'banco_id');
    }

    
}