<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cert_residency_details', function (Blueprint $table) {
            $table->string('purpose');
            $table->string('resident_years');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cert_residency_details', function (Blueprint $table) {
            $table->dropColumn(['purpose', 'resident_years']);
        });
    }
};
