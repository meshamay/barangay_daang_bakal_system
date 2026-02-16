<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Complaint>
 */
class ComplaintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['Pending', 'In Progress', 'Completed']);
        $complaintType = $this->faker->randomElement(['Community Issues', 'Physical Harrasments', 'Neighbor Dispute', 'Money Problems', 'Misbehavior', 'Others']);

        return [
            'user_id' => User::factory(),
            'transaction_no' => 'COM-' . strtoupper(substr(str_replace(' ', '', $complaintType), 0, 3)) . '-' . $this->faker->unique()->numberBetween(100, 999),
            'incident_date' => $this->faker->date(),
            'incident_time' => $this->faker->time(),
            'defendant_name' => $this->faker->name(),
            'defendant_address' => $this->faker->address(),
            'level_urgency' => $this->faker->randomElement(['Low', 'Medium', 'High']),
            'complaint_type' => $complaintType,
            'complaint_statement' => $this->faker->paragraph(3),
            'status' => $status,
            'date_completed' => $status === 'Completed' ? $this->faker->dateTimeThisMonth() : null,
        ];
    }
}