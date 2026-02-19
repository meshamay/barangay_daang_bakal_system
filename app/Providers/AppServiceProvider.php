<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; 
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