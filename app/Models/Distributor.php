<?php

namespace App\Models;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Distributor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone'
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
