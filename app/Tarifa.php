<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    protected $table = "tarifas";
    protected $fillable = [
        'transportista_id',
        'destino_id',
        'tar_usa',
        'tar_mex',
        'retencion',
        'moneda',
        'tc_fijo',
        'iva'
    ];

    public function transportista()
    {
        return $this->belongsTo(Transportista::class);
    }

    public function destino()
    {
        return $this->belongsTo(Destino::class);
    }
}
