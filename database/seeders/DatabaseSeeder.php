<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::table('users')->insert([
            'name' => 'Aditya Putra Aji Nur Alamsyah',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'admin',
        ]);
        DB::table('users')->insert([
            'name' => 'Dika Putra Pratama',
            'email' => 'petugas@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'petugas',
        ]);
        DB::table('users')->insert([
            'name' => 'Bagas Aditya Pratama',
            'email' => 'user@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'user',
        ]);
    }
}
