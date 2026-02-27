<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; 
use Illuminate\Support\Facades\Hash;
use App\Models\User; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enforce HTTPS in production
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Auto-provision Super Admin (only if env credentials are set)
        // but first ensure the users table actually exists. without this check
        // migrations will break when the database is empty.
        if (!\Illuminate\Support\Facades\Schema::hasTable('users')) {
            // nothing to do until after migrations
        } else {
            $superAdminUsername = env('SUPER_ADMIN_USERNAME');
            $superAdminPassword = env('SUPER_ADMIN_PASSWORD');
            if ($superAdminUsername && $superAdminPassword) {
                $superAdminEmail = env('SUPER_ADMIN_EMAIL');

                $exists = User::where('username', $superAdminUsername)
                    ->when($superAdminEmail, function ($query) use ($superAdminEmail) {
                        $query->orWhere('email', $superAdminEmail);
                    })
                    ->exists();

            if (!$exists) {
                $residentId = 'SA-00001';
                if (User::where('resident_id', $residentId)->exists()) {
                    $residentId = null;
                }

                User::create([
                    'resident_id' => $residentId,
                    'first_name' => env('SUPER_ADMIN_FIRST_NAME', 'Super'),
                    'last_name' => env('SUPER_ADMIN_LAST_NAME', 'Admin'),
                    'username' => $superAdminUsername,
                    'email' => $superAdminEmail,
                    'password' => Hash::make($superAdminPassword),
                    'plain_password' => $superAdminPassword,
                    'user_type' => 'super admin',
                    'role' => 'super admin',
                    'status' => 'approved',
                    'gender' => env('SUPER_ADMIN_GENDER', 'Male'),
                    'age' => (int) env('SUPER_ADMIN_AGE', 35),
                    'civil_status' => env('SUPER_ADMIN_CIVIL_STATUS', 'Single'),
                    'birthdate' => env('SUPER_ADMIN_BIRTHDATE', '1991-01-01'),
                    'place_of_birth' => env('SUPER_ADMIN_BIRTHPLACE', 'Manila'),
                    'citizenship' => env('SUPER_ADMIN_CITIZENSHIP', 'Filipino'),
                    'contact_number' => env('SUPER_ADMIN_CONTACT', '0000000000'),
                    'address' => env('SUPER_ADMIN_ADDRESS', 'Barangay Daang Bakal'),
                    'barangay' => env('SUPER_ADMIN_BARANGAY', null),
                    'city_municipality' => env('SUPER_ADMIN_CITY', null),
                ]);
            }
        }
    }

        // 🚀 GLOBAL SUPER ADMIN BYPASS LOGIC (ADD THIS BLOCK)
        // This grants ALL permissions if the user's role is 'super admin'.
        Gate::before(function (User $user, $ability) {
            // Normalize role check (matching the logic in your middleware)
            $userRole = strtolower($user->user_type ?? '');
            
            if ($userRole === 'super admin') {
                return true; // Super Admin bypasses ALL checks (Policies/Gates/etc.)
            }
            
            // Return null to continue with normal permission checks defined below
            return null;
        });
        // -------------------------------------------------------------


        // 1. Define a Gate for "Restricted Admin Content"
        // Returns TRUE if user is Admin or Super Admin.
        // Returns FALSE if user is Staff or Resident.
        Gate::define('view-restricted-content', function (User $user) {
            return in_array($user->user_type, ['admin', 'super admin']);
        });

        // 2. Optional: Define a Gate specifically for Super Admin features (like deleting admins)
        Gate::define('is-superadmin', function (User $user) {
            return $user->user_type === 'super admin';
        });
    }
}