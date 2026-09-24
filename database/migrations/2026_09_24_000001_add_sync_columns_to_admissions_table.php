<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('admissions')) {
            return;
        }

        Schema::table('admissions', function (Blueprint $table): void {
            if (! Schema::hasColumn('admissions', 'sync_uuid')) {
                $table->uuid('sync_uuid')->nullable()->unique()->after('id');
            }
            if (! Schema::hasColumn('admissions', 'sync_status')) {
                $table->enum('sync_status', ['pending', 'synced', 'failed'])->default('pending')->after('sync_uuid');
            }
            if (! Schema::hasColumn('admissions', 'sync_origin')) {
                $table->string('sync_origin')->default('local')->after('sync_status');
            }
            if (! Schema::hasColumn('admissions', 'sync_updated_at')) {
                $table->timestamp('sync_updated_at')->nullable()->after('sync_origin');
            }
            if (! Schema::hasColumn('admissions', 'remote_id')) {
                $table->unsignedBigInteger('remote_id')->nullable()->after('sync_updated_at');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('admissions')) {
            return;
        }

        Schema::table('admissions', function (Blueprint $table): void {
            foreach (['sync_uuid', 'sync_status', 'sync_origin', 'sync_updated_at', 'remote_id'] as $column) {
                if (Schema::hasColumn('admissions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
