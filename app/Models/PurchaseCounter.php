<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseCounter extends Model
{
    protected $fillable = ['year_month', 'last_number'];
}
