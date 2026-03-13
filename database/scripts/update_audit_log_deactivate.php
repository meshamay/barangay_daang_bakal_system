<?php

use Illuminate\Support\Facades\DB;

// Run with: php artisan tinker --execute="require 'database/scripts/update_audit_log_deactivate.php';"

DB::table('audit_logs')
    ->where('action', 'Deactivate Staff')
    ->update(['description' => 'Super Admin deactivated a staff member’s account']);

echo "Audit log deactivation descriptions updated.\n";
