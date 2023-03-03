<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    public function quantity(){
        return $this->belongsTo(Quantity::class);
    }


    public function trader(){
        return $this->belongsTo(Trader::class);
    }
}
