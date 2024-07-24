<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Molecula extends Model
{
    protected $table = 'Molecula1';

    protected $fillable = [
        "id",
        "molecula1",
        "bol",
        "litros",
        "rate1",
        "created_at",
        "update_at",
        "total1",
        "ferjr",
        "total2",
        "total_final"
    ];

    
}