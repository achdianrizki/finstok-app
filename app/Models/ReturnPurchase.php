<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnPurchase extends Model
{
    use HasFactory;

    protected $fillable = ['purchase_id', 'supplier_id', 'return_date', 'reason', 'total_return'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'return_purchase_items')->withPivot('qty', 'price_per_item');
    }
}
