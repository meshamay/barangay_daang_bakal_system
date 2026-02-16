<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\User;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $creator = User::whereIn('user_type', ['admin', 'super admin'])->first();

        if (!$creator) {
            $this->command->warn('⚠️ No admin user found. Please seed users first.');
            return;
        }

        Announcement::create([
            'title' => 'Community Cleanup Drive',
            'content' => "Join us this Saturday for a community-wide cleanup event. Bags and gloves will be provided. Let's make our barangay beautiful!",
            'start_date' => '2023-11-01',
            'end_date' => '2023-11-10',
            'status' => 'active',
            'created_by' => $creator->id,
        ]);
    }
}
