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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->date('incident_date');
            $table->time('incident_time');
            $table->string('defendant_name');
            $table->string('defendant_address');
            $table->enum('level_urgency', ['Low', 'Medium', 'High'])->default('Medium');
            $table->enum('complaint_type', ['Community Issues', 'Physical Harrasments', 'Neighbor Dispute', 'Money Problems', 'Misbehavior', 'Others']);
            $table->text('complaint_statement');
            $table->enum('status', ['Pending', 'In Progress', 'Completed'])->default('Pending');
            $table->timestamp('date_completed')->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};