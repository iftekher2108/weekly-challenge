<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'user_name' => 'superadmin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'super-admin',
            'company_id' => null, // Super admin doesn't belong to any company
        ]);

        $this->command->info('Super Admin user created successfully!');
        $this->command->info('Email: superadmin@example.com');
        $this->command->info('Password: password123');
    }
}
