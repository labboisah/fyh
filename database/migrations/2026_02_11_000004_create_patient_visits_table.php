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
        Schema::create('patient_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->dateTime('visit_date');
            $table->string('visit_type'); // e.g., 'Consultation', 'Follow-up', 'Emergency'
            $table->text('reason_for_visit')->nullable();
            $table->text('clinical_notes')->nullable();
            $table->enum('status', ['Active', 'Transferred', 'Admitted', 'Discharged'])->default('Active');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_visits');
    }
};
