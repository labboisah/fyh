<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'muhammad.muntaka@fayhos.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('fayhos@2026'),
            ]
        );

        // Assign administrator role
        $adminRole = Role::where('name', 'administrator')->first();
        if ($adminRole && !$admin->hasRole('administrator')) {
            $admin->assignRole($adminRole);
        }

        $syncableModels = [];
        foreach(User::find(1)->getModels() as $model) {
            $syncableModels[] = $model['table'];
        }


        foreach ($syncableModels as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    if (!Schema::hasColumn($table->getTable(), 'sync_uuid')) {
                        $table->uuid('sync_uuid')->nullable()->unique()->after('id');
                    }
                    if (!Schema::hasColumn($table->getTable(), 'sync_status')) {
                        $table->enum('sync_status', ['pending', 'synced', 'failed'])->default('pending')->after('sync_uuid');
                    }
                    if (!Schema::hasColumn($table->getTable(), 'sync_origin')) {
                        $table->string('sync_origin')->default('local')->after('sync_status');
                    }
                    if (!Schema::hasColumn($table->getTable(), 'sync_updated_at')) {
                        $table->timestamp('sync_updated_at')->nullable()->after('sync_origin');
                    }
                    if (!Schema::hasColumn($table->getTable(), 'remote_id')) {
                        $table->unsignedBigInteger('remote_id')->nullable()->after('sync_updated_at');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $syncableModels = [
            'patients',
            'patient_admissions',
            'patient_visits',
            'payments',
            'bills',
            'prescriptions',
            'vital_signs',
            'observations',
            'antenatal_cares',
            'labours',
            'labour_progresses',
            'labour_progress',
            'deliveries',
            'investigation_requests',
            'investigation_results',
            'fluid_balances',
            'newborn_examinations',
            'drug_charts',
            'discharges',
            'diagnoses',
            'continuations',
            'child_follow_ups',
        ];

        foreach ($syncableModels as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropColumnIfExists('sync_uuid');
                    $table->dropColumnIfExists('sync_status');
                    $table->dropColumnIfExists('sync_origin');
                    $table->dropColumnIfExists('sync_updated_at');
                    $table->dropColumnIfExists('remote_id');
                });
            }
        }
    }
};
