<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ProductName' => $this->faker->words(3, true),
            'ProductDescription' => $this->faker->sentence(10),
            'Price' => $this->faker->randomFloat(2, 10, 1000),
            'OtherAttributes' => json_encode([ 
                'brand' => $this->faker->randomElement(['Bershka', 'Zara', 'Gucci', 'Prada']),
                'collection' => $this->faker->randomElement(['Pants', 'Shoes', 'Shirt', 'Accessory']),
                'genre'=> $this->faker->randomElement(['M', 'F'])
            ]),
        ];
    }
}
