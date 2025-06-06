<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@farm.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'),
            ]
        );

        $role = Role::firstOrCreate(['name' => 'admin']);
        $admin->assignRole($role);
    }
} 