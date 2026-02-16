<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BarangayOfficials;

class BarangayOfficialsFactory extends Factory
{
    protected $model = BarangayOfficials::class;

    public function definition(): array
    {
        return [
            'last_name' => $this->faker->lastName,
            'first_name' => $this->faker->firstName,
            'middle_initial' => strtoupper($this->faker->randomLetter),
            'position' => $this->faker->jobTitle,
            'photo_path' => null,
            'created_by' => 1, 
        ];
    }
}
