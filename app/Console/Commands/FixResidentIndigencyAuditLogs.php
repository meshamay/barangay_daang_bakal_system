<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixResidentIndigencyAuditLogs extends Command
{
    protected $signature = 'fix:auditlog-resident-indigency';
    protected $description = 'Fix resident document request audit logs for Indigency to use correct description';

    public function handle()
    {
        $updated = DB::table('audit_logs')
            ->join('users', 'audit_logs.user_id', '=', 'users.id')
            ->whereRaw("LOWER(audit_logs.action) = 'document request submitted'")
            ->whereRaw("LOWER(audit_logs.description) = 'certificate of indigency'")
            ->where(function($query) {
                $query->whereRaw("LOWER(users.role) = 'resident'")
                      ->orWhereRaw("LOWER(users.user_type) = 'resident'");
            })
            ->update([
                'description' => 'Indigency Clearance',
            ]);

        $this->info("Updated $updated resident document request audit logs for Indigency.");
    }
}
