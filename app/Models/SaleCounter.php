<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleCounter extends Model
{
    protected $fillable = ['year_month', 'last_number'];
}
