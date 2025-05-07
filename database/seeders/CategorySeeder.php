<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            'title'         => 'electronics',
            'description'   => 'this is elecronics',
            'photo'         => 'categories/fruit.jpg',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        DB::table('categories')->insert([
            'title'         => 'jewelery',
            'description'   => 'this is jewelery',
            'photo'         => 'categories/spices.jpg',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        DB::table('categories')->insert([
            'title'         => 'men \'s clothing',
            'description'   => 'this is men\'s clothing',
            'photo'         => 'categories/vegetables.jpg',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        DB::table('categories')->insert([
            'title'         => 'women\'s clothing',
            'description'   => 'this is women\'s clothing',
            'photo'         => 'categories/citrus_fruit.jpg',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
