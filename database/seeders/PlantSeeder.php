<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plant;
use App\Models\User;

class PlantSeeder extends Seeder
{
    public function run()
    {
        $sellers = User::role('seller')->get();

        $plants = [
            [
                'name' => 'Snake Plant',
                'category' => 'indoor',
                'price' => 29.99,
                'quantity' => 50,
                'description' => 'Low-maintenance indoor plant, perfect for air purification.',
                'care_instructions' => json_encode([
                    'water' => 'Water every 2-3 weeks',
                    'light' => 'Low to bright indirect light',
                    'temperature' => '60-85°F',
                    'humidity' => 'Any humidity level'
                ]),
                'is_active' => true
            ],
            [
                'name' => 'Peace Lily',
                'category' => 'indoor',
                'price' => 34.99,
                'quantity' => 30,
                'description' => 'Beautiful flowering indoor plant with air-purifying qualities.',
                'care_instructions' => json_encode([
                    'water' => 'Keep soil moist',
                    'light' => 'Medium to low indirect light',
                    'temperature' => '65-80°F',
                    'humidity' => 'High humidity preferred'
                ]),
                'is_active' => true
            ],
            [
                'name' => 'Rose Bush',
                'category' => 'outdoor',
                'price' => 45.99,
                'quantity' => 20,
                'description' => 'Classic garden rose bush with fragrant blooms.',
                'care_instructions' => json_encode([
                    'water' => 'Water deeply once a week',
                    'light' => 'Full sun',
                    'temperature' => '60-75°F',
                    'pruning' => 'Regular pruning needed'
                ]),
                'is_active' => true
            ]
        ];

        foreach ($sellers as $seller) {
            foreach ($plants as $plant) {
                Plant::create(array_merge($plant, [
                    'user_id' => $seller->id
                ]));
            }
        }
    }
} 