<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateAuditLogDescriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auditlog:update-descriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update old audit log descriptions for staff add/edit actions to new wording.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $add = DB::table('audit_logs')
            ->where('action', 'Add Staff')
            ->update(['description' => 'Super Admin added a new staff member']);
        $edit = DB::table('audit_logs')
            ->where('action', 'Edit Staff')
            ->update(['description' => 'Super Admin updated a staff member’s details']);
        $this->info("Updated $add 'Add Staff' and $edit 'Edit Staff' audit log records.");
    }
}
