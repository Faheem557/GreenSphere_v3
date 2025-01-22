<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plant;
use App\Models\User;
use App\Models\PlantCareGuide;
use App\Models\PlantMaintenance;
use Faker\Factory as Faker;

class PlantSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        // Get all sellers
        $sellers = User::role('seller')->get();
        
        // Plant name templates for more realistic names
        $plantPrefixes = ['Royal', 'Golden', 'Silver', 'Purple', 'Red', 'White', 'Pink', 'Blue', 'Dwarf', 'Giant'];
        $plantTypes = [
            'Palm', 'Fern', 'Bamboo', 'Rose', 'Lily', 'Orchid', 'Cactus', 'Succulent', 'Bonsai', 
            'Monstera', 'Philodendron', 'Pothos', 'Aloe', 'Jade', 'Snake Plant', 'Peace Lily'
        ];

        foreach ($sellers as $seller) {
            // Create 10 plants for each seller
            for ($i = 0; $i < 10; $i++) {
                $category = $faker->randomElement(array_keys(Plant::CATEGORIES));
                $subCategories = Plant::SUB_CATEGORIES[$category] ?? [];
                $subCategory = !empty($subCategories) ? $faker->randomElement(array_keys($subCategories)) : null;
                
                // Generate specifications
                $specifications = [
                    'origin' => $faker->country(),
                    'scientific_name' => $faker->text(20),
                    'family' => $faker->word(),
                    'growth_habit' => $faker->randomElement(['Upright', 'Trailing', 'Climbing', 'Rosette']),
                ];
                
                // Generate delivery info
                $deliveryInfo = [
                    'packaging' => $faker->randomElement(['Eco-friendly box', 'Plastic pot', 'Ceramic pot']),
                    'shipping_restrictions' => $faker->randomElement(['None', 'No international shipping', 'Mainland only']),
                    'handling_time' => $faker->randomElement(['1-2 business days', '2-3 business days', '3-5 business days']),
                ];
                
                // Generate care instructions
                $careInstructions = [
                    'watering' => $faker->paragraph(),
                    'sunlight' => $faker->paragraph(),
                    'temperature' => $faker->paragraph(),
                    'soil' => $faker->paragraph(),
                ];
                
                // Create the plant with new fields
                $plant = Plant::create([
                    'user_id' => $seller->id,
                    'name' => $faker->randomElement(['Monstera', 'Snake Plant', 'Peace Lily', 'Spider Plant', 'Pothos', 'ZZ Plant', 'Fiddle Leaf Fig', 'Rubber Plant', 'Chinese Evergreen', 'Philodendron']),
                    'category' => $category,
                    'sub_category' => $subCategory,
                    'description' => $faker->paragraph(3),
                    'price' => $faker->randomFloat(2, 20, 500),
                    'quantity' => $faker->numberBetween(0, 100),
                    'image' => null,
                    'specifications' => $specifications,
                    'delivery_info' => $deliveryInfo,
                    'care_instructions' => $careInstructions,
                    'is_active' => true,
                    'care_level' => $faker->randomElement(array_keys(Plant::CARE_LEVELS)),
                    'water_needs' => $faker->randomElement(array_keys(Plant::WATER_NEEDS)),
                    'light_needs' => $faker->randomElement(array_keys(Plant::LIGHT_NEEDS)),
                    'season' => $faker->randomElement(['Spring', 'Summer', 'Fall', 'Winter', 'All Seasons']),
                    'height' => $faker->randomFloat(2, 10, 200),
                    'pot_size' => $faker->randomElement(['Small', 'Medium', 'Large']),
                    'maturity_time' => $faker->randomElement(['3-6 months', '6-12 months', '1-2 years']),
                    'is_featured' => $faker->boolean(20),
                    
                    // New fields
                    'soil_type' => $faker->randomElement(array_keys(Plant::SOIL_TYPES)),
                    'temperature_range' => $faker->numberBetween(15, 25) . '-' . $faker->numberBetween(26, 35) . '°C',
                    'humidity_requirements' => $faker->numberBetween(40, 80) . '%',
                    'fertilizer_needs' => $faker->randomElement(['Monthly during growing season', 'Every 2-3 months', 'Quarterly', 'Bi-annual']),
                    'blooming_season' => $faker->randomElement(['Spring', 'Summer', 'Fall', 'Winter', 'Year-round', 'Spring-Summer']),
                    'mature_height' => $faker->numberBetween(10, 200) . ' cm',
                    'growth_rate' => $faker->randomElement(array_keys(Plant::GROWTH_RATES)),
                    'pet_friendly' => $faker->boolean(),
                    'maintenance_level' => $faker->randomElement(array_keys(Plant::MAINTENANCE_LEVELS)),
                    'propagation_method' => $faker->randomElement(['Stem cuttings', 'Division', 'Leaf cuttings', 'Air layering', 'Seeds'])
                ]);

                // Create care guide for each plant
                $plant->careGuide()->create([
                    'watering_schedule' => Plant::WATER_NEEDS[$plant->water_needs],
                    'light_requirements' => Plant::LIGHT_NEEDS[$plant->light_needs],
                    'temperature_range' => [
                        'min' => $faker->numberBetween(15, 20),
                        'max' => $faker->numberBetween(25, 30),
                        'ideal' => $faker->numberBetween(20, 25)
                    ],
                    'humidity_level' => $faker->numberBetween(40, 80) . '%',
                    'fertilizing_schedule' => $faker->randomElement(['Monthly', 'Bi-monthly', 'Quarterly']),
                    'pruning_tips' => $faker->paragraph(),
                    'common_problems' => [
                        'pest_issues' => $faker->randomElements(['Spider mites', 'Mealybugs', 'Scale insects', 'Fungus gnats'], 2),
                        'diseases' => $faker->randomElements(['Root rot', 'Leaf spot', 'Powdery mildew'], 2),
                        'environmental_issues' => $faker->randomElements(['Overwatering', 'Underwatering', 'Low humidity', 'Too much direct sun'], 2)
                    ],
                    'seasonal_care' => [
                        'spring' => $faker->paragraph(),
                        'summer' => $faker->paragraph(),
                        'fall' => $faker->paragraph(),
                        'winter' => $faker->paragraph()
                    ]
                ]);

                // Create plant maintenance schedule
                PlantMaintenance::create([
                    'plant_id' => $plant->id,
                    'user_id' => $seller->id,
                    'watering_schedule' => [
                        'frequency' => $faker->randomElement(['daily', 'weekly', 'monthly']),
                        'amount' => $faker->randomElement(['light', 'moderate', 'heavy'])
                    ],
                    'fertilizing_schedule' => [
                        'frequency' => 'Every ' . $faker->numberBetween(2, 8) . ' weeks',
                        'type' => $faker->randomElement(['NPK', 'Organic', 'Liquid'])
                    ],
                    'pruning_schedule' => $faker->randomElement(['Monthly', 'Quarterly', 'Bi-annually']),
                    'repotting_schedule' => $faker->randomElement(['Yearly', 'Every 2 years', 'As needed']),
                    'last_watered_at' => now()->subDays($faker->numberBetween(1, 7)),
                    'last_fertilized_at' => now()->subDays($faker->numberBetween(1, 30)),
                    'next_maintenance_date' => now()->addDays($faker->numberBetween(1, 14))
                ]);
            }
        }
    }
} 