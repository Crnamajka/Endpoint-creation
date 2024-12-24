<?php

namespace Database\Factories;

use App\Models\ShoppingCart; // Corregir el nombre del modelo
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartItem>
 */
class CartItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'CartID' => ShoppingCart::factory(),
            'VariantID' => ProductVariant::factory(), 
            'Quantity' => $this->faker->numberBetween(1, 5), 
            'UnitPrice' => $this->faker->randomFloat(2, 10, 1000), 
        ];
    }
}
