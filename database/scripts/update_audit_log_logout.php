<?php

use Illuminate\Support\Facades\DB;

// Run with: php artisan tinker --execute="require 'database/scripts/update_audit_log_logout.php';"

DB::table('audit_logs')
    ->whereIn('action', ['Logout', 'log out'])
    ->whereIn('description', ['Admin/Super Admin logged out', 'User logged out'])
    ->update(['description' => 'Admin/Super Admin logged out']);

echo "Audit log logout descriptions updated.\n";
