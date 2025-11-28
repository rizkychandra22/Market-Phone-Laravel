<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            // 'role' => 'Super Admin',
            'phone' => '08951662773',
            'address' => 'Sukabumi',
            'email' => 'super123@gmail.com',
            'password' => Hash::make('password123') 
        ]);
        User::create([
            'name' => 'Fadhillah Dinurahman',
            'username' => 'fadhillahdnrr',
            // 'role' => 'Seller',
            'phone' => '087786799710',
            'address' => 'Cisaat, Sukabumi',
            'email' => 'haiyamhaha02@gmail.com',
            'password' => Hash::make('password123')
        ]);
        User::create([
            'name' => 'Rizky Chandra',
            'username' => 'chndr',
            // 'role' => 'Customer',
            'phone' => '085766442233',
            'address' => 'Man 2 Sukabumi',
            'email' => 'chandra224@gmail.com',
            'password' => Hash::make('password123')
        ]);
    }
}
