<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'price',
        'stok',
        'category_id',
        'warehouse_id'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class);
    }
}
