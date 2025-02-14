<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'total_price',
        'sub_total',
        'total_discount',
        'status',
        'tax',
        'information',
        'total_qty',
        'purchase_number',
        'purchase_date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function finance()
    {
        return $this->hasOne(Finance::class, 'purchase_id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_purchase')->withPivot('qty');
    }
}
