<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Username' => $this->faker->unique()->userName, 
            'Email' => $this->faker->unique()->safeEmail, 
            'PasswordHash' => Hash::make('password'),     
            'CreatedDate' => now(),                
        ];
    }
}
