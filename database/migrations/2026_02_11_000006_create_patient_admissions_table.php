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
        Schema::create('patient_admissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->dateTime('admission_date');
            $table->dateTime('discharge_date')->nullable();
            $table->text('reason_for_admission');
            $table->string('department')->nullable();
            $table->string('bed_number')->nullable();
            $table->enum('status', ['Admitted', 'Discharged', 'Transferred'])->default('Admitted');
            $table->text('notes')->nullable();
            $table->foreignId('admitted_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('discharged_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_admissions');
    }
};
