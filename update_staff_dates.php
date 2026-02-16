<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

// Update all staff (non-resident users)
$updated = User::whereNotNull('role')
    ->where('role', '!=', 'resident')
    ->update(['created_at' => now(), 'updated_at' => now()]);

echo "✓ Updated $updated staff members' dates to today (" . now()->format('Y-m-d') . ")\n";
