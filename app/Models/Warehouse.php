<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'slug'
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($warehouse) {
            $warehouse->slug = Str::slug($warehouse->name);
        });

        static::updating(function ($warehouse) {
            $warehouse->slug = Str::slug($warehouse->name);
        });
    }

    public function item_warehouse()
    {
        return $this->belongsToMany(Item::class, 'item_warehouse')->withPivot('stock', 'price_per_item');
    }
}
