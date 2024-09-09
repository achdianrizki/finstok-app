<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'payment_method',
        'buyer',
        'item_id',
        'distributor_id',
        'diskon',
        'amount',
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
