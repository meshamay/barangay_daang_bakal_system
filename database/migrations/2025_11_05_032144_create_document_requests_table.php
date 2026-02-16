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
        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();
            
            
            $table->foreignId('resident_id')->constrained('users')->onDelete('cascade');

            
            $table->string('tracking_number')->unique()->nullable();

            $table->string('document_type'); 
            $table->string('purpose');       
            
            
            $table->enum('status', ['pending', 'in progress', 'completed', 'rejected'])->default('pending');
            $table->timestamp('date_requested')->useCurrent(); 

            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');

            $table->softDeletes(); 
            $table->timestamps();  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_requests');
    }
};