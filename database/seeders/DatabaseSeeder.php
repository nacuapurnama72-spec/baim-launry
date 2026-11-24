<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User Admin Default
        User::create([
            'name' => 'Admin Laundry',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'role' => 'admin',
            'password' => Hash::make('password123'),
        ]);
        User::create([
            'name' => 'Kasir Laundry',
            'email' => 'kasir@gmail.com',
            'username' => 'kasir',
            'role' => 'kasir',
            'password' => Hash::make('password123'),
        ]);

        // 2. Panggil Seeder Layanan Laundry
        $this->call([
            ServiceSeeder::class,
        ]);
    }
}
