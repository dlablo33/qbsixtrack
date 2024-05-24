<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'tipo_usuario',
        'create_at',
        
    ];
}
