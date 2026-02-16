<?php

// database/migrations/..._add_valid_id_and_voter_to_document_requests_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('document_requests', function (Blueprint $table) {
            $table->string('valid_id_number', 50)->nullable();
            $table->string('registered_voter', 100)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('document_requests', function (Blueprint $table) {
            $table->dropColumn(['valid_id_number', 'registered_voter']);
        });
    }
};