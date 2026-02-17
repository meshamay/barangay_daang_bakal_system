<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin account
        User::create([
            'resident_id' => 'SA-00001',
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@daangbakal.gov',
            'password' => Hash::make('SuperAdmin@2026'),
            'plain_password' => 'SuperAdmin@2026',
            'user_type' => 'super admin',
            'role' => 'super admin',
            'status' => 'approved',
            'gender' => 'Male',
            'age' => 35,
            'civil_status' => 'Single',
            'birthdate' => '1991-01-01',
            'place_of_birth' => 'Manila',
            'citizenship' => 'Filipino',
            'contact_number' => '09171234567',
            'address' => 'Barangay Daang Bakal Office',
        ]);

        // Create regular users
        User::factory()->count(10)->create();
    }
}