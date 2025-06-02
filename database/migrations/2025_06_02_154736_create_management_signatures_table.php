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
        Schema::create('management_signatures', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(); // Optional title for the signature
            $table->string('full_name');
            $table->string('position')->nullable();
            $table->foreignId('signature_id')->nullable()->constrained('assets')->nullOnDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete(); // User ID or name of the creator
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete(); // User ID or name of the last updater
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete(); // User ID or name of the delete
            $table->timestamps();
            $table->softDeletes(); // For soft delete functionality
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('management_signatures');
    }
};
