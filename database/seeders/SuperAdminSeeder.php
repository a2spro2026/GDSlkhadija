<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'superadmine@gds.com'],
            [
                'name' => 'Super Administrateur',
                'email' => 'superadmine@gds.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+212 6 00 00 00 01',
                'is_active' => true,
            ]
        );
    }
}
