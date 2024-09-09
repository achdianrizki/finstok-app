<?php

namespace App\Models;

use App\Models\Sale;
use App\Models\Purchase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Finance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'income', 
        'expense', 
        'profit_loss', 
        'purchase_id', 
        'sales_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sales_id');
    }
}
