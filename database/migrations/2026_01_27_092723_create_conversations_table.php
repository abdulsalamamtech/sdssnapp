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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sent_by')->constrained('users')->onDelete('cascade');
            $table->json('sent_to')->comment('users to send message');
            $table->string('subject')->default("Notification");
            $table->text('message');
            $table->string('reason')->default('reminder');
            $table->string('status')->default('sent');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
