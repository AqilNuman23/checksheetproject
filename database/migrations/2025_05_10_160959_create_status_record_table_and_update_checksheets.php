<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Create status table
        Schema::create('status_record', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        // Insert status records
        DB::table('status_record')->insert([
            ['id' => 1, 'name' => 'Draft'],
            ['id' => 2, 'name' => 'On Hold'],
            ['id' => 3, 'name' => 'Approved'],
            ['id' => 4, 'name' => 'Rejected'],
        ]);

        // Add temporary status column to checksheets
        Schema::table('checksheets', function (Blueprint $table) {
            $table->unsignedBigInteger('status_temp')->nullable()->after('updated_by');
        });

        // Update status based on current status values
        DB::table('checksheets')
            ->where('status', 'submitted')
            ->update(['status_temp' => 1]); // Draft

        DB::table('checksheets')
            ->where('status', 'reviewed')
            ->update(['status_temp' => 2]); // On Hold

        DB::table('checksheets')
            ->where('status', 'rejected')
            ->update(['status_temp' => 4]); // Rejected

        // Set default status for any other status values (e.g., null or unexpected values)
        DB::table('checksheets')
            ->whereNull('status')
            ->update(['status_temp' => 1]); // Default to Draft

        // Drop old status column and rename status to status
        Schema::table('checksheets', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->renameColumn('status_temp', 'status_record_id');
        });

        // Add foreign key constraint
        Schema::table('checksheets', function (Blueprint $table) {
            $table->foreign('status_record_id')->references('id')->on('status_record')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        // Revert changes to checksheets table
        Schema::table('checksheets', function (Blueprint $table) {
            $table->dropForeign(['status']);
            $table->string('status')->nullable()->after('updated_by');
        });

        // Restore original status values (approximate, as original data may be lost)
        DB::table('checksheets')
            ->where('status', 1)
            ->update(['status' => 'submitted']);

        DB::table('checksheets')
            ->where('status', 2)
            ->update(['status' => 'reviewed']);

        DB::table('checksheets')
            ->where('status', 4)
            ->update(['status' => 'rejected']);

        // Drop status table
        Schema::dropIfExists('status_record');
    }
};