<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('colors')->insert([
            'name'      => 'red',
            'code'      => '#FF0000',
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);
        DB::table('colors')->insert([
            'name'      => 'green',
            'code'      => '#00FF00',
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);
        DB::table('colors')->insert([
            'name'      => 'blue',
            'code'      => '#0000FF',
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);
        DB::table('colors')->insert([
            'name'      => 'white',
            'code'      => '#FFFFFF',
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);
    }
}
