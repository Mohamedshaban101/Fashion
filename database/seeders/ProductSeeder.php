<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            'name'          => 'apple',
            'description'   => 'this is apple',
            'regular_price' => 900,
            'sale_price'    => 1000,
            'discount'      => 10,
            'quantity'      => 20,
            'photo'         => 'products/artichoke.jpg',
            'category_id'   => 1,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        DB::table('products')->insert([
            'name'          => 'banana',
            'description'   => 'this is banana',
            'regular_price' => 90,
            'sale_price'    => 100,
            'discount'      => 10,
            'quantity'      => 30,
            'photo'         => 'products/cabbage.jpg',
            'category_id'   => 2,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        DB::table('products')->insert([
            'name'          => 'kiwi',
            'description'   => 'this is apple',
            'regular_price' => 100,
            'sale_price'    => 95,
            'discount'      => 5,
            'quantity'      => 20,
            'photo'         => 'products/carrot.jpg',
            'category_id'   => 3,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
