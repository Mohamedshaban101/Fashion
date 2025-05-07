<?php

namespace App\Models;

use App\Models\Color;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name' , 'description' , 'regular_price' , 'sale_price' , 'discount' , 'quantity' , 'photo' , 'category_id'];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function colors(){
        return $this->belongsToMany(Color::class , 'product_color');
    }
}
