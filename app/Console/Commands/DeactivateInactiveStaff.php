<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class DeactivateInactiveStaff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'staff:deactivate-inactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate staff members who have not logged in for 3 months';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threeMonthsAgo = Carbon::now()->subMonths(3);
        
        $inactiveStaff = User::where('user_type', 'admin')
            ->where('status', '!=', 'inactive')
            ->where(function($query) use ($threeMonthsAgo) {
                $query->whereNull('last_login_at')
                      ->orWhere('last_login_at', '<', $threeMonthsAgo);
            })
            ->get();

        $count = 0;
        foreach ($inactiveStaff as $staff) {
            $staff->update(['status' => 'inactive']);
            $count++;
        }

        $this->info("Deactivated {$count} staff member(s) due to inactivity.");
        return 0;
    }
}
