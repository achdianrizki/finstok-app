<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory;
    use SoftDeletes;

    
    protected $fillable = [
        'supplier_code',
        'name',
        'npwp',
        'phone',
        'fax_nomor',
        'address',
        'city',
        'province',
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
