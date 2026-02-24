<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cert_indigency_details', function (Blueprint $table) {
            if (!Schema::hasColumn('cert_indigency_details', 'other_purpose')) {
                $table->string('other_purpose')->nullable();
            }
            if (!Schema::hasColumn('cert_indigency_details', 'certificate_of_being_indigent')) {
                $table->string('certificate_of_being_indigent')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cert_indigency_details', function (Blueprint $table) {
            if (Schema::hasColumn('cert_indigency_details', 'other_purpose')) {
                $table->dropColumn('other_purpose');
            }
            if (Schema::hasColumn('cert_indigency_details', 'certificate_of_being_indigent')) {
                $table->dropColumn('certificate_of_being_indigent');
            }
        });
    }
};
