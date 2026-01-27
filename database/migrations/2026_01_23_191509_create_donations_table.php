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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('email')->nullable();
            $table->string('full_name');
            // amount
            $table->decimal('amount', 10, 2)->nullable()->default(20000.00)->comment('Amount');
            // currency
            $table->string('currency')->nullable()->default('NGN')->comment('Currency in Naira');
            $table->text('reason_for_donation')->nullable();
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'rejected', 'approved', 'paid'])->default('pending');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete(); // User ID or name of the creator
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete(); // User ID or name of the last updater
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete(); // User ID or name of the delete
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
