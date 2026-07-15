<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create 1 fake project manager
        User::factory()
            ->projectManager()
            ->create();

        //     User::create([
        //     'name' => 'Krishna',
        //     'email' => 'krishna@gmail.com',
        //     'password' => Hash::make('Strong123@'),
        //     'role' => 'project_manager',
        // ]);

        // Create 3 fake employees
        User::factory()
            ->employee()
            ->count(3)
            ->create();
    }
}
