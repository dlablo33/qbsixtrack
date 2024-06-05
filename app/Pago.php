<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Factura;

class Pago extends Model
{
    protected $table = "pagos";
    protected $fillable = [
        'id',
        'factura_id',
        'monto',
        'referencia',
        'fecha_pago',
    ];

    public function factura()
    {
        return $this->hasmany(Factura::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Customer::class); 
}
}