<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentBatchItem extends Model
{
    protected $fillable = ['batch_id', 'bol_number', 'amount'];

    public function batch()
    {
        return $this->belongsTo(PaymentBatch::class, 'batch_id');
    }
}