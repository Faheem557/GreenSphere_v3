<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create Sellers
        $sellers = [
            [
                'name' => 'Green Garden Nursery',
                'email' => 'garden@example.com',
                'password' => Hash::make('password'),
                'preferences' => json_encode(['specialties' => ['indoor', 'outdoor']]),
                'location' => json_encode([
                    'address' => '123 Garden Street',
                    'city' => 'Green City',
                    'state' => 'Nature State',
                    'zip' => '12345'
                ])
            ],
            [
                'name' => 'Plant Paradise',
                'email' => 'paradise@example.com',
                'password' => Hash::make('password'),
                'preferences' => json_encode(['specialties' => ['flowering', 'indoor']]),
                'location' => json_encode([
                    'address' => '456 Paradise Ave',
                    'city' => 'Flora City',
                    'state' => 'Garden State',
                    'zip' => '67890'
                ])
            ]
        ];

        foreach ($sellers as $sellerData) {
            $seller = User::create($sellerData);
            $seller->assignRole('seller');
        }

        // Create Regular Users
        $users = [
            [
                'name' => 'Plant Lover',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'preferences' => json_encode(['interests' => ['indoor', 'low-maintenance']]),
                'location' => json_encode([
                    'address' => '789 User Street',
                    'city' => 'Plant City',
                    'state' => 'Green State',
                    'zip' => '34567'
                ])
            ],
            [
                'name' => 'Garden Enthusiast',
                'email' => 'enthusiast@example.com',
                'password' => Hash::make('password'),
                'preferences' => json_encode(['interests' => ['outdoor', 'flowering']]),
                'location' => json_encode([
                    'address' => '321 Garden Road',
                    'city' => 'Bloom City',
                    'state' => 'Flora State',
                    'zip' => '89012'
                ])
            ]
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);
            $user->assignRole('user');
        }
    }
} 