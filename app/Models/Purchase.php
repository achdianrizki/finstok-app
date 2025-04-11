<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'supplier_id',
        'total_price',
        'sub_total',
        'total_discount1',
        'total_discount2',
        'total_discount3',
        'status',
        'tax',
        'tax_type',
        'information',
        'total_qty',
        'purchase_number',
        'purchase_date',
        'due_date_duration',
        'due_date',
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
        return $this->belongsToMany(Item::class, 'item_purchase')
            ->withPivot('qty', 'price_per_item', 'discount1', 'discount2', 'discount3', 'ad', 'warehouse_id')
            ->withTimestamps();
    }


    public function payments()
    {
        return $this->hasMany(OutgoingPayment::class);
    }

    public function outgoingPayments()
    {
        return $this->hasMany(OutgoingPayment::class, 'purchase_id');
    }
}
