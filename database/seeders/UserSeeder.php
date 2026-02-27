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
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@disnaker.go.id'],
            [
                'name' => 'IT Dinas (Super Admin)',
                'password' => bcrypt('password'),
                'role' => 'super_admin',
            ]
        );

        \App\Models\User::updateOrCreate(
            ['email' => 'staf_phi@disnaker.go.id'],
            [
                'name' => 'Staf Bidang PHI',
                'password' => bcrypt('password'),
                'role' => 'staf',
            ]
        );

        \App\Models\User::updateOrCreate(
            ['email' => 'pejabat@disnaker.go.id'],
            [
                'name' => 'Pejabat Disnaker',
                'password' => bcrypt('password'),
                'role' => 'pejabat',
            ]
        );

        \App\Models\User::updateOrCreate(
            ['email' => 'lpk@disnaker.go.id'],
            [
                'name' => 'LPK Disnaker',
                'password' => bcrypt('password'),
                'role' => 'penta',
            ]
        );

        \App\Models\User::updateOrCreate(
            ['email' => 'bkk@disnaker.go.id'],
            [
                'name' => 'BKK Disnaker',
                'password' => bcrypt('password'),
                'role' => 'penta',
            ]
        );
    }
}
