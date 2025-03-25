<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'purchase_price',
        'unit',
        'stock',
        'description',
        'supplier_id',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function purchase()
    {
        return $this->hasMany(Purchase::class);
    }

    public function sale()
    {
        return $this->hasMany(Sale::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'item_supplier');
    }

    public function purchases()
    {
        return $this->belongsToMany(Purchase::class, 'item_purchase')->withPivot('qty', 'price_per_item', 'discount1', 'discount2', 'discount3', 'ad', 'warehouse_id');
    }

    public function item_warehouse()
    {
        return $this->belongsToMany(Warehouse::class, 'item_warehouse')->withPivot('stock', 'price_per_item', 'warehouse_id');
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'item_sale')->withPivot('qty_sold', 'sale_price', 'discount1', 'discount2', 'discount3');
    }
}
