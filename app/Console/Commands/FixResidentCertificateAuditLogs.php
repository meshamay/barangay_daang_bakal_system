<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixResidentCertificateAuditLogs extends Command
{
    protected $signature = 'fix:auditlog-resident-certificate';
    protected $description = 'Fix resident document request audit logs for Residency to use correct description';

    public function handle()
    {
        $updated = DB::table('audit_logs')
            ->join('users', 'audit_logs.user_id', '=', 'users.id')
            ->whereRaw("LOWER(audit_logs.action) = 'document request submitted'")
            ->whereRaw("LOWER(audit_logs.description) = 'certificate of residency'")
            ->where(function($query) {
                $query->whereRaw("LOWER(users.role) = 'resident'")
                      ->orWhereRaw("LOWER(users.user_type) = 'resident'");
            })
            ->update([
                'description' => 'Resident Certificate',
            ]);

        $this->info("Updated $updated resident document request audit logs for Residency.");
    }
}
