<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssignRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::find(1);
        $seller     = User::find(2);
        $customer   = User::find(3);

        if ($superAdmin) {
            $superAdmin->assignRole('Super Admin');
        }

        if ($seller) {
            $seller->assignRole('Seller');
        }

        if ($customer) {
            $customer->assignRole('Customer');
        }
    }
}
