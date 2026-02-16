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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // --- System Identifiers ---
            $table->string('resident_id')->nullable()->unique(); // e.g., RS-00001
            $table->string('username')->unique();
            $table->string('password');
            $table->string('user_type')->default('resident'); // admin, super admin, resident
            $table->string('role')->default('resident');      // resident, official
            $table->string('status')->default('pending');     // pending, approved, archived, rejected

            // --- Personal Information ---
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();

            $table->string('gender');
            $table->integer('age');
            $table->string('civil_status');
            $table->date('birthdate'); // Matches $request->dob mapping in Controller
            $table->string('place_of_birth');
            $table->string('citizenship')->nullable();

            // --- Contact Information ---
            $table->string('contact_number');
            $table->string('email')->nullable()->unique();
            $table->string('address');

            // --- File Paths ---
            $table->string('photo_path')->nullable();
            $table->string('id_front_path')->nullable();
            $table->string('id_back_path')->nullable();

            // --- System Timestamps ---
            $table->rememberToken();
            $table->softDeletes(); // Required for Archive functionality
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
