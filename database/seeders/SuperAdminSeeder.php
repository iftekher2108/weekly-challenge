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
        DB::transaction(function () {
          $user =  User::create([
                'name' => 'Iftekher Mahmud',
                'user_name' => 'iftekher2108',
                'email' => 'iftekhermahmud1@gmail.com',
                'password' => Hash::make('Iftekher21082002'),
                'super_admin' => true,
                'is_active' => true,
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
    }
}
