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
        Schema::create('vital_signs_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_visit_id')->constrained('patient_visits')->onDelete('cascade');
            $table->foreignId('requested_by')->constrained('users')->onDelete('restrict');
            $table->enum('status', ['Pending', 'Completed', 'Cancelled'])->default('Pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vital_signs_requests');
    }
};
