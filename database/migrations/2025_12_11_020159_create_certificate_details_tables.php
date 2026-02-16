<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cert_certificate_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('document_request_id')->constrained()->onDelete('cascade');
        $table->string('purpose');
        $table->string('cedula_no')->nullable();
        $table->string('is_voter')->nullable(); 
        $table->timestamps();
    });

        Schema::create('cert_clearance_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_request_id')->constrained()->onDelete('cascade');
            $table->string('purpose');
            $table->string('cedula_no')->nullable();
            $table->timestamps();
        });

        Schema::create('cert_indigency_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_request_id')->constrained()->onDelete('cascade');
            $table->string('purpose');
            $table->string('resident_years');
            $table->timestamps();
        });

        Schema::create('cert_residency_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_request_id')->constrained()->onDelete('cascade');
            $table->string('civil_status');
            $table->string('citizenship');
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('cert_clearance_details');
        Schema::dropIfExists('cert_indigency_details');
        Schema::dropIfExists('cert_residency_details');
        Schema::dropIfExists('cert_Certificate_details');
    }
};