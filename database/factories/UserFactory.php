<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'last_name' => fake()->lastName(),
            'first_name' => fake()->firstName(),
            'middle_name' => fake()->optional()->lastName(),
            
            'age' => fake()->numberBetween(18, 80), 
            
            'birthdate' => fake()->date(),
            'place_of_birth' => fake()->city(),
            'gender' => fake()->randomElement(['Male', 'Female']),
            'civil_status' => fake()->randomElement(['Single', 'Married', 'Divorced', 'Widowed']),
            'citizenship' => 'Filipino',
            'email' => fake()->unique()->safeEmail(),
            'contact_number' => '09' . fake()->randomNumber(9, true),
            'address' => fake()->address(),
            'username' => fake()->unique()->userName(),
            'password' => Hash::make('password123'),
            'user_type' => 'user', 
        ];
    }
}