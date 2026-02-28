<?php

use Illuminate\Support\Facades\DB;

// Run with: php artisan tinker < database/scripts/fix_resident_logout_auditlogs.php

DB::table('audit_logs')
    ->whereRaw("LOWER(action) = 'logout'")
    ->whereRaw("LOWER(description) = 'user logged out'")
    ->join('users', 'audit_logs.user_id', '=', 'users.id')
    ->where(function($query) {
        $query->whereRaw("LOWER(users.role) = 'resident'")
              ->orWhereRaw("LOWER(users.user_type) = 'resident'");
    })
    ->update([
        'action' => 'Log Out',
        'description' => 'Resident logged out',
    ]);

echo "Resident logout audit logs fixed.\n";
