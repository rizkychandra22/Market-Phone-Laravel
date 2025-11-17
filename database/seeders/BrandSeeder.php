<?php

namespace Database\Seeders;

use App\Models\brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        brand::create([
            'name' => 'Samsung'
        ]);
        brand::create([
            'name' => 'Iphone'
        ]);
        brand::create([
            'name' => 'Vivo'
        ]);
        brand::create([
            'name' => 'Oppo'
        ]);
        brand::create([
            'name' => 'Realme'
        ]);
        brand::create([
            'name' => 'Infinix'
        ]);
        brand::create([
            'name' => 'Xiaomi'
        ]);
    }
}
