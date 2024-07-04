<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Bluewi extends Model
{
    protected $table = "bluewis";

    protected $fillable = [
        'order_number',
        'bol_number',
        'bol_version',
        'order_type',
        'status',
        'bol_date',
        'position_holder',
        'supplier',
        'customer',
        'destination',
        'carrier',
        'po',
        'truck',
        'trailer',
        'bay',
        'product',
        'scheduled_amount_usg',
        'gross_usg',
        'net_usg',
        'temperature',
        'gravity',
        'tank'
    ];
    

}