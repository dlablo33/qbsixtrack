<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Precio extends Model
{
    protected $table = "products";
    protected $fillable = 
    [ 
    "id",
    "clv_producto",
    "nombre",
    "created_at",
    "updated",
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class); // One price belongs to one product
    }



}