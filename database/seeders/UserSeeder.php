<?php

namespace Database\Seeders;

use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'AdminUser',
            'email' => 'admin@example.com',
            'password' => Hash::make('Hoge1234'),
            'is_admin' => true,
        ]);
    }
}
