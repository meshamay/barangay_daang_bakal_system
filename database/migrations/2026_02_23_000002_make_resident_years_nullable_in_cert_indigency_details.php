<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cert_indigency_details', function (Blueprint $table) {
            $table->string('resident_years')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('cert_indigency_details', function (Blueprint $table) {
            $table->string('resident_years')->nullable(false)->change();
        });
    }
};
