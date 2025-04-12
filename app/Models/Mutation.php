<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mutation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mutations';
    protected $fillable = [
        'item_id',
        'from_warehouse_id',
        'to_warehouse_id',
        'qty',
        'note',
        'mutated_at',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }
}
