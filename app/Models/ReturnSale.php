<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnSale extends Model
{
    use HasFactory;

    protected $fillable = ['sale_id', 'buyer_id', 'salesma_id','return_date', 'reason', 'total_return'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    public function salesman()
    {
        return $this->belongsTo(Salesman::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'return_sale_items')->withPivot('qty', 'price_per_item');
    }
}
