<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->truncate();

        $user = User::query()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        $user->assignRole('admin');

        $user = User::query()->create([
            'name' => 'manager',
            'email' => 'manager@manager.com',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole('manager');
    }
}
