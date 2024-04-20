<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{

    protected $table = 'invoices_line'; // Define explícitamente el nombre de la tabla (opcional)

    protected $fillable = [
        'amount',
        'linkedtxn',
        'detailtype',
        'invoice_id' ,
        'subtotallinedetail',
        'customfield',
        'line_index ',
        'id',
        'salesitemlinedetail',
    ];

}