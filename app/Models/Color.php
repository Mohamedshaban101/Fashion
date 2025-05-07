<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = ['name' , 'code'];

    public function products(){
        return $this->belongsToMany(Product::class, 'product_color');
    }
}
