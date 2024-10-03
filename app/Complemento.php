<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Pago;

class Complemento extends Model
{
    protected $table = 'complementos';

    protected $fillable = [
        'pago_id', 
        'banco_proveniente', 
        'numero_cuenta', 
        'serial_baunche'
    ];

    // Relación con el modelo Pago (si existe)
    public function pago()
    {
        return $this->belongsTo(Pago::class, 'pago_id');
    }
}
