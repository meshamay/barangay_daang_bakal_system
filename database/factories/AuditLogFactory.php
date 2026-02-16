<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'action' => $this->faker->randomElement(['Document Request Submitted', 'Complaint Submitted', 'Status Updated']),
            'description' => $this->faker->sentence(),
        ];
    }
}
