<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_id', 
        'total_price', 
        'price',
        'status',
        'supplier_name',
        'qty',
        'invoice_number'
    ];

    public function user(){
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
}
