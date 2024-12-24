<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShoppingCart>
 */
class ShoppingCartFactory extends Factory
{
    /**
     * Define the model's default state
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'UserID' => User::factory(), 
            'CreatedDate' => $this->faker->dateTimeBetween('-1 year', 'now'), 
            'Status' => $this->faker->randomElement(['open', 'closed', 'abandoned']),
        ];
    }
}
