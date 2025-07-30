<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // ========================== super admin ==========================
        DB::transaction(function () {
            $user =  User::create([
                'name' => 'Iftekher Mahmud',
                'user_name' => 'iftekher2108',
                'email' => 'iftekhermahmud1@gmail.com',
                'password' => Hash::make('Iftekher21082002'),
                'is_active' => true,
                'role' => 'super-admin',
                'company_id' => null, // Super admin doesn't belong to any company
            ]);

            Profile::create([
                'user_id' => $user->id,
                'bal_point' => 999999999
            ]);
        });
        $this->command->info('Super Admin user created successfully!');
        $this->command->info('Email: iftekhermahmud1@gmail.com');
        $this->command->info('Password: Iftekher21082002');
        // ================================== super admin ===========================


        // ========================== user-1 ==========================
        DB::transaction(function () {
            $user =  User::create([
                'name' => 'User',
                'user_name' => 'user',
                'email' => 'user@gmail.com',
                'password' => Hash::make('user'),
                'is_active' => true,
                'role' => 'user',
                'company_id' => '1,2',
            ]);

            Profile::create([
                'user_id' => $user->id,
                'bal_point' => 1500
            ]);
        });

        $this->command->info('User user created successfully!');
        $this->command->info('Email: user@gmail.com');
        $this->command->info('Password: user');
        // ========================== user-1 ==========================

        // ========================== user-2 ==========================
        DB::transaction(function () {
            $user =  User::create([
                'name' => 'User-2',
                'user_name' => 'user-2',
                'email' => 'user-2@gmail.com',
                'password' => Hash::make('user-2'),
                'is_active' => true,
                'role' => 'user',
                'company_id' => '2',
            ]);

            Profile::create([
                'user_id' => $user->id,
                'bal_point' => 1500
            ]);
        });

        $this->command->info('User user created successfully!');
        $this->command->info('Email: user-2@gmail.com');
        $this->command->info('Password: user-2');
        // ========================== user-2 ==========================


        // ========================== user-3 ==========================
        DB::transaction(function () {
            $user =  User::create([
                'name' => 'User-3',
                'user_name' => 'user-3',
                'email' => 'user-3@gmail.com',
                'password' => Hash::make('user-3'),
                'is_active' => true,
                'role' => 'user',
                'company_id' => '1',
            ]);

            Profile::create([
                'user_id' => $user->id,
                'bal_point' => 1500
            ]);
        });

        $this->command->info('User user created successfully!');
        $this->command->info('Email: user-3@gmail.com');
        $this->command->info('Password: user-3');
        // ========================== user-3 ==========================

        // ========================== user-4 ==========================
        DB::transaction(function () {
            $user =  User::create([
                'name' => 'User-4',
                'user_name' => 'user-4',
                'email' => 'user-4@gmail.com',
                'password' => Hash::make('user-4'),
                'is_active' => true,
                'role' => 'user',
                'company_id' => '3',
            ]);

            Profile::create([
                'user_id' => $user->id,
                'bal_point' => 1500
            ]);
        });

        $this->command->info('User user created successfully!');
        $this->command->info('Email: user-4@gmail.com');
        $this->command->info('Password: user-4');
        // ========================== user-4 ==========================
    }
}
