<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'purchase_id',
        'receipt_number',
        'payment_date',
        'note',
        'payment_method',
        'total_price',
        'total_unpaid',
        'amount_paid',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
