<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class transacciones extends Model
{
    protected $table = "transacciones";
    protected $fillable = 
    [ 
    "id",
    "banco",
    "created_at",
    "updated_at",
    ];
}