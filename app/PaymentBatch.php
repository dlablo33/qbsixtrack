<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PaymentBatchItem;

class PaymentBatch extends Model
{
    protected $fillable = ['batch_number', 'total_amount'];

    public function items()
    {
        return $this->hasMany(PaymentBatchItem::class, 'batch_id');
    }
}
