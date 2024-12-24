<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product; 

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ProductID' => Product::factory(),
            'Color' => $this->faker->safeColorName(),
            'Size' => $this->faker->randomElement(['S', 'M', 'L', 'XL']),
            'StockQuantity' => $this->faker->numberBetween(0, 100),
        ];
    }
}
