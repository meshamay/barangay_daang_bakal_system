<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixResidentLoginAuditLogs extends Command
{
    protected $signature = 'fix:auditlog-resident-login';
    protected $description = 'Fix resident login audit logs to use correct action and description';

    public function handle()
    {
        $updated = DB::table('audit_logs')
            ->join('users', 'audit_logs.user_id', '=', 'users.id')
            ->whereRaw("LOWER(audit_logs.action) = 'login'")
            ->whereRaw("LOWER(audit_logs.description) = 'user logged in'")
            ->where(function($query) {
                $query->whereRaw("LOWER(users.role) = 'resident'")
                      ->orWhereRaw("LOWER(users.user_type) = 'resident'");
            })
            ->update([
                'action' => 'Log In',
                'description' => 'Resident logged in',
            ]);

        $this->info("Updated $updated resident login audit logs.");
    }
}
