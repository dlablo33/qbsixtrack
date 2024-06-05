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

    public function customer()
    {
        return $this->belongsTo(Precio::class, 'cliente_id');
    }


    public function items()
    {
        return $this->hasMany(Invoice::class);
    }



}