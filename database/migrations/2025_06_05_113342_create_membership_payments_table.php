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
        Schema::create('membership_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('membership_id')->constrained('memberships')->onDelete('cascade');
            // payment_type [new, renewal]
            $table->string('payment_type')->default('new');
            $table->decimal('amount', 10, 2);
            $table->enum('status', 
                [
                    'pending', 
                    'successful', 
                    'cancelled', 
                    'suspended', 
                    'rejected'
                ])
                ->default('pending');
            $table->string('reference')->unique();
            $table->string('payment_method')->default('online');
            $table->string('payment_provider')->default('paystack');
            $table->json('data')->nullable(); // response data from payment server
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_payments');
    }
};
