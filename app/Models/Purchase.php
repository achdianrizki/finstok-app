<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 
        'item_id', 
        'category_id', 
        'price', 
        'amount', 
        'purchase_type', 
        'supplier_name'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function finance()
    {
        return $this->hasOne(Finance::class, 'purchase_id');
    }
}
