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
        $faker = $this->faker ?: \Faker\Factory::create();
        return [
            'last_name' => $faker->lastName(),
            'first_name' => $faker->firstName(),
            'middle_name' => $faker->optional()->lastName(),
            'age' => $faker->numberBetween(18, 80),
            'birthdate' => $faker->date(),
            'place_of_birth' => $faker->city(),
            'gender' => $faker->randomElement(['Male', 'Female']),
            'civil_status' => $faker->randomElement(['Single', 'Married', 'Divorced', 'Widowed']),
            'citizenship' => 'Filipino',
            'email' => $faker->unique()->safeEmail(),
            'contact_number' => '09' . $faker->randomNumber(9, true),
            'address' => $faker->address(),
            'username' => $faker->unique()->userName(),
            'password' => Hash::make('password123'),
            'user_type' => 'user',
        ];
    }
}