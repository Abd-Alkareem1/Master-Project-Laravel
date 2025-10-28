<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        User::create([
            'name' => 'admin.com',
            'email' => 'admin@gmail.com',
            'phone' => '09887909878',
            'address' => 'Yangon',
            'gender' => 'male',
            'role' => 'admin',
            'password' => Hash::make('password')
        ]);

        Category::create([
            'name' => 'Chicken Pizza'
        ]);

        Category::create([
            'name' => 'Port Pizza'
        ]);

        Category::create([
            'name' => 'Cheesy Pizza'
        ]);
    }
}
