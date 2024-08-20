<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quickbook extends Model
{
    // Nombre de la tabla asociada al modelo
    protected $table = 'quickbook';

    // Clave primaria de la tabla
    protected $primaryKey = 'id';

    // Indica que la clave primaria no es autoincremental (ya que es varchar)
    public $incrementing = false;

    // Tipo de la clave primaria (int, string, etc.)
    protected $keyType = 'int';

    // Deshabilita las marcas de tiempo automáticas (created_at, updated_at)
    public $timestamps = false;

    // Lista de campos que se pueden asignar de forma masiva
    protected $fillable = [
        'id',
        'sync_token',
        'meta_data',
        'custom_field',
        'domain',
        'txn_date',
        'doc_number',
        'due_date',
        'total_amt',
        'balance',
        'allow_online_ach_payment',
        'allow_online_credit_card_payment',
        'allow_online_payment',
        'allow_ipn_payment',
        'print_status',
        'email_status',
        'bill_email',
        'ship_addr',
        'bill_addr',
        'private_note',
        'customer_memo',
        'sales_term_ref',
        'customer_ref',
        'apply_tax_after_discount',
    ];

    // Casts para los campos JSON y booleanos
    protected $casts = [
        'meta_data' => 'array',
        'custom_field' => 'array',
        'ship_addr' => 'array',
        'bill_addr' => 'array',
        'customer_memo' => 'array',
        'sales_term_ref' => 'array',
        'customer_ref' => 'array',
        'allow_online_ach_payment' => 'boolean',
        'allow_online_credit_card_payment' => 'boolean',
        'allow_online_payment' => 'boolean',
        'allow_ipn_payment' => 'boolean',
        'apply_tax_after_discount' => 'boolean',
    ];
}

