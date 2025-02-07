<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'distributor_id',
        'item_id',
        'qty_sold',
        'payment_method',
        'payment_status',
        'discount',
        'down_payment',
        'remaining_payment',
        'total_price'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'distributor_id');
    }

    public function finance()
    {
        return $this->hasOne(Finance::class, 'sales_id');
    }
}
