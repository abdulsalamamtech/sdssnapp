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
        Schema::create('certification_requests', function (Blueprint $table) {
            // user_id
            // certification_id [from UI]
            // full_name [from user account]
            // signature_image_id (from user) [from UI]
            // reason_for_certification [text editor] [from UI]
            // management_note 
            // status (pending, rejected, approved, paid)
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('certification_id')->constrained('certifications')->onDelete('cascade');
            $table->string('full_name');
            $table->foreignId('user_signature_id')->constrained('certifications')->onDelete('cascade');
            $table->foreignId('credential_id')->constrained('credentials')->onDelete('cascade');
            $table->text('reason_for_certification')->nullable();
            $table->text('management_note')->nullable();
            $table->enum('status', ['pending', 'rejected', 'approved', 'paid'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certification_requests');
    }
};
