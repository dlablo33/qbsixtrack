<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
    protected $table = "products";
    protected $fillable = 
    [ 
    "id",
    "clv_producto",
    "nombre",
    "created_at",
    "updated_at",
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_id');
    }


}