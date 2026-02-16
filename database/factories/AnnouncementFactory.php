<?php

namespace Database\Factories;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    public function definition(): array
    {
        $creator = User::inRandomOrder()->first()?->id ?? User::factory()->create()->id;

        return [
            'title' => $this->faker->sentence(6),
            'content' => $this->faker->paragraphs(3, true),
            'date_started' => $this->faker->dateTimeBetween('-1 week', '+1 week'),
            'date_end' => $this->faker->dateTimeBetween('+2 days', '+2 weeks'),
            'status' => $this->faker->randomElement(['active', 'inactive', 'archived']),
            'created_by' => $creator,
        ];
    }
}
