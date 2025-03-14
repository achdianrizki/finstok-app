<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $fillable = [
        'buyer_id',
        'salesman_id',
        'sale_number',
        'total_price',
        'sub_total',
        'discount1_value',
        'discount2_value',
        'discount3_value',
        'total_discount',
        'sale_date',
        'status',
        'tax',
        'information',
        'qty_sold',
        'due_date_duration',
        'due_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class)->withPivot('qty_sold', 'sale_price', 'discount1', 'discount2', 'discount3');;
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    public function salesman()
    {
        return $this->belongsTo(Salesman::class);
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'distributor_id');
    }

    public function finance()
    {
        return $this->hasOne(Finance::class, 'sales_id');
    }

    public function incomingPayments()
    {
        return $this->hasMany(IncomingPayment::class, 'sale_id');
    }
}
