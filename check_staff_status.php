<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use App\Models\User;

$staff = User::where('user_type', 'admin')->select('id', 'username', 'status')->get();
echo "Current staff status values:\n";
foreach($staff as $s) {
    echo "- {$s->username}: '{$s->status}'\n";
}
