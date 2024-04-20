<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = "customers";
    protected $fillable = [
        'name',
        'quickbooks_id',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'realm_id',
        'SyncToken',

    ];

}
