<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = "settings";

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
