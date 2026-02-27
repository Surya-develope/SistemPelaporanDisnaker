<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'IT Dinas (Super Admin)',
            'email' => 'admin@disnaker.go.id',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
        ]);

        \App\Models\User::create([
            'name' => 'Staf Bidang PHI',
            'email' => 'staf_phi@disnaker.go.id',
            'password' => bcrypt('password'),
            'role' => 'staf',
        ]);

        \App\Models\User::create([
            'name' => 'Pejabat Disnaker',
            'email' => 'pejabat@disnaker.go.id',
            'password' => bcrypt('password'),
            'role' => 'pejabat',
        ]);
    }
}
