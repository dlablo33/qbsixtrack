<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PrecioMolecula extends Model
{
    protected $table = 'PrecioMolecula';

    protected $fillable = [
        "id",
        "molecula",
        "precio",
        "usuario",
        "updated_at",
        "created_at",
    ];

    
}