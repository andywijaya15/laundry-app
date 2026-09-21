<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed 5 services dasar laundry
        DB::table('services')->insert([
            ['name' => 'Cuci Kering', 'unit' => 'kg', 'price' => 5000, 'is_active' => true],
            ['name' => 'Cuci Setrika', 'unit' => 'kg', 'price' => 3000, 'is_active' => true],
            ['name' => 'Cuci Express', 'unit' => 'item', 'price' => 15000, 'is_active' => true],
            ['name' => 'Cucian Khusus', 'unit' => 'item', 'price' => 20000, 'is_active' => true],
            ['name' => 'Pengeringan', 'unit' => 'kg', 'price' => 2000, 'is_active' => true],
        ]);

        // Seed 2 users: owner dan staff
        User::create([
            'name' => 'Owner Laundry',
            'email' => 'owner@laundry.test',
            'password' => bcrypt('password123'),
            'role' => 'owner',
            'is_active' => true,
            'outlet_id' => 1,
        ]);

        User::create([
            'name' => 'Staff Laundry',
            'email' => 'staff@laundry.test',
            'password' => bcrypt('password123'),
            'role' => 'staff',
            'is_active' => true,
            'outlet_id' => 1,
        ]);
    }
}
