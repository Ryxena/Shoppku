<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'profile' => 'public/profile/lorelei-1730565048790.png',
                'password' => Hash::make('12345678'),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'profile' => 'public/profile/lorelei-1730565048790.png',
                'password' => Hash::make('12345678'),
            ],
            [
                'name' => 'Bob Wilson',
                'email' => 'bob@example.com',
                'profile' => 'public/profile/lorelei-1730565048790.png',
                'password' => Hash::make('12345678'),
            ],
            [
                'name' => 'Alice Brown',
                'email' => 'alice@example.com',
                'profile' => 'public/profile/lorelei-1730565048790.png',
                'password' => Hash::make('12345678'),
            ],
            [
                'name' => 'Charlie Davis',
                'email' => 'charlie@example.com',
                'profile' => 'public/profile/lorelei-1730565048790.png',
                'password' => Hash::make('12345678'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
