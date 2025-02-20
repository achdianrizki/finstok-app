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
        'selling_price',
        'unit',
        'stock',
        'description',
        'supplier_id',
        'category_id',
        'warehouse_id'
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
        return $this->belongsToMany(Purchase::class, 'item_purchase')->withPivot('qty', 'price_per_item');
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'item_sale')->withPivot('qty_sold');
    }
}
