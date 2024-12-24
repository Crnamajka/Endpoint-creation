<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'UserID' => User::factory(), 
            'OrderDate' => now(),
            'TotalAmount' => $this->faker->randomFloat(2, 20, 500), 
            'OrderStatus' => $this->faker->randomElement(['pending', 'completed', 'shipped', 'cancelled']), 
            'PaymentMethod' => $this->faker->randomElement(['credit_card', 'paypal', 'bank_transfer']),
            'ShippingAddress' => $this->faker->address(),
        ];
    }
}
