<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_record_checksheet', function (Blueprint $table) {
            // make a log record for status changes in checksheets with a foreign key of user_id, product_id, checksheet_id, status_record_id
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // user who changed the status
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // product related to the checksheet
            $table->foreignId('checksheet_id')->constrained('checksheets')->onDelete('cascade'); // checksheet related to the status change
            $table->foreignId('status_record_id')->constrained('status_record')->onDelete('cascade'); // status record ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_record_checksheet');
    }
};
