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
        Schema::table('cert_indigency_details', function (Blueprint $table) {
            $table->string('indigency_category')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cert_indigency_details', function (Blueprint $table) {
            $table->dropColumn('indigency_category');
        });
    }
};
