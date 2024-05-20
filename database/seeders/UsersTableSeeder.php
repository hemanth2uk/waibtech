<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@demo.com',
            'password' => Hash::make('123456'), // Encrypt password
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
