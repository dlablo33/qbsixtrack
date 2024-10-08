<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurrencyConversion extends Model
{
    // Si la tabla no sigue la convención plural, especifica el nombre de la tabla
    protected $table = 'currency_conversions'; // Solo si la tabla no sigue el nombre plural por defecto

    // Si la tabla tiene campos de timestamps, asegúrate de que estén habilitados
    public $timestamps = true; // Esto es true por defecto, puedes omitirlo si lo deseas

    // Especifica los campos que son asignables en masa
    protected $fillable = [
        'amount',
        'from_currency',
        'to_currency',
        'exchange_rate',
        'converted_amount',
        'empresa_cuenta_id'
    ];

    // Aquí puedes agregar cualquier relación o método adicional según tus necesidades
}
