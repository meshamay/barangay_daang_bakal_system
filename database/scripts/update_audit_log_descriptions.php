<?php

use Illuminate\Support\Facades\DB;

// Run with: php artisan tinker < database/scripts/update_audit_log_descriptions.php

DB::table('audit_logs')
    ->where('action', 'Add Staff')
    ->update(['description' => 'Super Admin added a new staff member']);

DB::table('audit_logs')
    ->where('action', 'Edit Staff')
    ->update(['description' => 'Super Admin updated a staff member’s details']);

echo "Audit log descriptions updated.\n";
