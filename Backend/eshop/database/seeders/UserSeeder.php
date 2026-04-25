<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@admin.admin'],
            [
                'name' => 'admin',
                'password' => 'adminpass',
                'role' => User::ROLE_ADMIN,
            ]
        );

        User::updateOrCreate(
            ['email' => 'test@test.test'],
            [
                'name' => 'Test User',
                'password' => '12345678',
                'role' => User::ROLE_USER,
            ]
        );
    }
}