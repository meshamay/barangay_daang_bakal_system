<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// Run with: php artisan tinker < database/scripts/update_resident_login_logout_auditlogs.php

DB::table('audit_logs')
    ->whereIn(DB::raw('LOWER(action)'), ['login', 'logout'])
    ->join('users', 'audit_logs.user_id', '=', 'users.id')
    ->where(function($query) {
        $query->where(DB::raw('LOWER(users.role)'), 'resident')
              ->orWhere(DB::raw('LOWER(users.user_type)'), 'resident');
    })
    ->update([
        'action' => DB::raw("CASE WHEN LOWER(audit_logs.action) = 'login' THEN 'Log In' ELSE 'Log Out' END"),
        'description' => DB::raw("CASE WHEN LOWER(audit_logs.action) = 'login' THEN 'Resident logged in' ELSE 'Resident logged out' END"),
    ]);

echo "Resident login/logout audit logs updated.\n";
