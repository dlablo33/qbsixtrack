<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
    use Notifiable;
    protected $table = "products";
    protected $guarded = [];
   // protected $fillable = ['id','ItemId', 'Name', 'Description', 'Active', 'FullyQualifiedName', 'Taxable', 'UnitPrice', 'Type', 'IncomeAccountRef', 'PurchaseCost', 'TrackQtyOnHand', 'domain', 'sparse', 'SyncToken'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
