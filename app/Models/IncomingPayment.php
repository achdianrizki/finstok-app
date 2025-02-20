<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'invoice_number',
        'payment_date',
        'payment_method',
        'bank_account_number',
        'payment_code',
        'remaining_payment',
        'pay_amount',
        'total_paid',
        'information',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
