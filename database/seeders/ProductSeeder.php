<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\Product::insert([
            ['name' => 'Sách Laravel', 'price' => 120000],
            ['name' => 'Khóa học PHP', 'price' => 450000],
            ['name' => 'USB 32GB', 'price' => 99000],
            ['name' => 'Áo thun Dev', 'price' => 150000],
        ]);
    }
}
