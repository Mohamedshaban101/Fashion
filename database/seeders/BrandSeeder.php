<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('brands')->insert([
            'title'         => 'brand1',
            'description'   => 'this is brand1',
            'photo'         => 'brands/red_pepper.jpg',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        DB::table('brands')->insert([
            'title'         => 'brand2',
            'description'   => 'this is brand2',
            'photo'         => 'brands/spinach.jpg',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        DB::table('brands')->insert([
            'title'         => 'brand3',
            'description'   => 'this is brand3',
            'photo'         => 'brands/tomatoes.jpg',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
