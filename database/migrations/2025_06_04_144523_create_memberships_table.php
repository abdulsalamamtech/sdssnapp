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
        Schema::create('memberships', function (Blueprint $table) {
            	// user_id
            // full_name
            // certification_request_id 
            // issued_on
            // expires_on
            // serial_no
            // qr_code
            // status [pending, paid]
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('certification_request_id')->constrained('certification_requests')->onDelete('cascade');
            $table->string('full_name');
            $table->string('serial_no');
            $table->date('issued_on');
            $table->date('expires_on');
            // config('app.frontend_certificate_verify_url') . $serial_no
            $table->string('qr_code');
            $table->enum('status', ['pending', 'paid'])->default('pending');
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
        Schema::dropIfExists('memberships');
    }
};
