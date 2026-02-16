<?php

namespace Database\Factories;

use App\Models\DocumentRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentRequestFactory extends Factory
{
    protected $model = DocumentRequest::class;

    public function definition(): array
    {
        return [
            'resident_id' => User::factory(),
            'document_type' => $this->faker->randomElement(['Barangay Clearance', 'Certificate of Residency', 'Indigency']),
            'purpose' => $this->faker->sentence(4),
            'date_requested' => now(),
            'status' => $this->faker->randomElement(['pending', 'in progress', 'completed']),
            'processed_by' => null, 
        ];
    }
}
