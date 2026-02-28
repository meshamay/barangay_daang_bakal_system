<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixResidentLogoutAuditLogs extends Command
{
    protected $signature = 'fix:auditlog-resident-logout';
    protected $description = 'Fix resident logout audit logs to use correct action and description';

    public function handle()
    {
        $updated = DB::table('audit_logs')
            ->join('users', 'audit_logs.user_id', '=', 'users.id')
            ->whereRaw("LOWER(audit_logs.action) = 'logout'")
            ->whereRaw("LOWER(audit_logs.description) = 'user logged out'")
            ->where(function($query) {
                $query->whereRaw("LOWER(users.role) = 'resident'")
                      ->orWhereRaw("LOWER(users.user_type) = 'resident'");
            })
            ->update([
                'action' => 'Log Out',
                'description' => 'Resident logged out',
            ]);

        $this->info("Updated $updated resident logout audit logs.");
    }
}
