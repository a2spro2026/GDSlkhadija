<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GhitanadaUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'ghitanada'],
            [
                'name' => 'Ghita Nada',
                'email' => 'ghitanada@gds-dlimi.ma',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+212 6 00 00 00 00',
                'is_active' => true,
            ]
        );
    }
}
