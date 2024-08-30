<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago_aduana extends Model
{
    protected $table = 'pagos_aduana';
    protected $fillable = ['bol_id', 'cantidad', 'fecha'];
}
