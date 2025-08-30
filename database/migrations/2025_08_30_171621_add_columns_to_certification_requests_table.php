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
        Schema::table('certification_requests', function (Blueprint $table) {
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete(); // User ID of the person who approved
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete(); // User ID of the person who rejected
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certification_requests', function (Blueprint $table) {
            $table->dropColumn(['approved_by', 'rejected_by']);
        });
    }
};
