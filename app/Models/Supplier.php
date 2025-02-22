<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'supplier_code',
        'name',
        'contact',
        'discount1',
        'discount2',
        'phone',
        'fax_nomor',
        'address',
        'city',
        'province',
        'payment_term',
        'status'
    ];

    public function item()
    {
        return $this->belongsToMany(Item::class, 'item_supplier');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function payments()
    {
        return $this->hasMany(OutgoingPayment::class);
    }
}
