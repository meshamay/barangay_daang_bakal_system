<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Models\User; 
use App\Channels\SmsChannel;
use Throwable;

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
        // Register custom notification channel
        Notification::extend('sms', function ($app) {
            return new SmsChannel();
        });

        // Enforce HTTPS in production
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Auto-provision Super Admin only when DB is ready.
        // This prevents build-time artisan commands (e.g., config:cache) from failing
        // when SQLite database file is not yet present in deployment environments.
        if ($this->canProvisionSuperAdmin()) {
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

        // Auto-provision Backup Admin (secondary admin with limited privileges)
        if ($this->canProvisionSuperAdmin()) {
            $backupAdminUsername = env('BACKUP_ADMIN_USERNAME');
            $backupAdminPassword = env('BACKUP_ADMIN_PASSWORD');

            if ($backupAdminUsername && $backupAdminPassword) {
                $backupAdminEmail = env('BACKUP_ADMIN_EMAIL');

                $exists = User::where('username', $backupAdminUsername)
                    ->when($backupAdminEmail, function ($query) use ($backupAdminEmail) {
                        $query->orWhere('email', $backupAdminEmail);
                    })
                    ->exists();

                if (!$exists) {
                    $residentId = 'BA-00001';
                    if (User::where('resident_id', $residentId)->exists()) {
                        $residentId = null;
                    }

                    User::create([
                        'resident_id' => $residentId,
                        'first_name' => env('BACKUP_ADMIN_FIRST_NAME', 'Backup'),
                        'last_name' => env('BACKUP_ADMIN_LAST_NAME', 'Admin'),
                        'username' => $backupAdminUsername,
                        'email' => $backupAdminEmail,
                        'password' => Hash::make($backupAdminPassword),
                        'plain_password' => $backupAdminPassword,
                        'user_type' => 'admin',
                        'role' => 'admin',
                        'status' => 'approved',
                        'gender' => env('BACKUP_ADMIN_GENDER', 'Male'),
                        'age' => (int) env('BACKUP_ADMIN_AGE', 30),
                        'civil_status' => env('BACKUP_ADMIN_CIVIL_STATUS', 'Single'),
                        'birthdate' => env('BACKUP_ADMIN_BIRTHDATE', '1996-01-01'),
                        'place_of_birth' => env('BACKUP_ADMIN_BIRTHPLACE', 'Manila'),
                        'citizenship' => env('BACKUP_ADMIN_CITIZENSHIP', 'Filipino'),
                        'contact_number' => env('BACKUP_ADMIN_CONTACT', '0000000000'),
                        'address' => env('BACKUP_ADMIN_ADDRESS', 'Barangay Daang Bakal'),
                        'barangay' => env('BACKUP_ADMIN_BARANGAY', null),
                        'city_municipality' => env('BACKUP_ADMIN_CITY', null),
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
            return $user->user_type === 'super admin' || ($user->user_type === 'admin' && $user->username === env('BACKUP_ADMIN_USERNAME'));
        });
    }

    private function canProvisionSuperAdmin(): bool
    {
        $defaultConnection = config('database.default');

        if ($defaultConnection === 'sqlite') {
            $sqlitePath = config('database.connections.sqlite.database');

            if ($sqlitePath && $sqlitePath !== ':memory:' && !file_exists($sqlitePath)) {
                return false;
            }
        }

        try {
            return \Illuminate\Support\Facades\Schema::hasTable('users');
        } catch (Throwable $exception) {
            return false;
        }
    }
}