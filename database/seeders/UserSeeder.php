<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;



class UserSeeder extends Seeder
{public function run()
    {
        User::create([
            'name' => 'User_2',
            'email' => 'user2@example.com',
            'password' => Hash::make('password'),
            'username' => 'user_@', // Assuming you want the username to be 'admin'
            'role' => 'user', // Set the role to 'admin'
        ]);
    }
}
