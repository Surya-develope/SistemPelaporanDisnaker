<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Administrator', 'email' => 'admin@disnaker.go.id', 'username' => 'admin', 'role' => 'admin'],
            ['name' => 'Petugas PENTA', 'email' => 'penta@disnaker.go.id', 'username' => 'penta', 'role' => 'penta'],
            ['name' => 'Petugas PHI', 'email' => 'phi@disnaker.go.id', 'username' => 'phi', 'role' => 'phi'],
            ['name' => 'Petugas LATTAS', 'email' => 'lattas@disnaker.go.id', 'username' => 'lattas', 'role' => 'lattas'],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
            ['email' => $u['email']],
            [
                'name' => $u['name'],
                'password' => Hash::make('123'),
                'role' => $u['role']
            ]
            );
        }
    }
}
