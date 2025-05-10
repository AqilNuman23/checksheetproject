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
        // Create companies table
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('contact_info')->nullable();
            $table->timestamps();
        });

        // Modify users table (assuming it already exists from Laravel Auth)
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('supplier')->after('password'); // admin, qe, supplier
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('set null'); // for suppliers
        });

        // Create products table
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Create checksheets table
        Schema::create('checksheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('qe_id')->constrained('users')->onDelete('cascade');
            $table->text('details')->nullable(); // size, dimension, etc.
            $table->string('document_path')->nullable(); // softcopy of checksheet
            $table->enum('status', ['submitted', 'reviewed', 'approved', 'rejected'])->default('submitted');
            $table->date('submission_date')->nullable();
            $table->date('warranty_expiry')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop checksheets table
        Schema::dropIfExists('checksheets');

        // Drop products table
        Schema::dropIfExists('products');

        // Remove columns from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn(['role', 'company_id']);
        });

        // Drop companies table
        Schema::dropIfExists('companies');
    }
};