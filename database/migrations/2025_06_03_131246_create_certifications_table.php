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
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('management_signature_id')->nullable()->constrained('management_signatures')->nullOnDelete('cascade');
            $table->string('organization_name')->nullable()->default('Spatial And Data Science Society Of Nigeria');
            $table->string('title')->unique()->nullable()->default('Certified Spatial and Data Scientist');
            $table->string('type')->nullable()->default('Professional Certification');
            // code
            $table->string('abbreviation_code')->nullable()->default('CER');
            // individual or organization
            $table->string('for')->default('individual');
            // duration in months
            $table->integer('duration')->nullable()->default(2);
            // length of month, years, or weeks
            $table->string('duration_unit')->nullable()->default('years')->comment('duration in weeks, month, year');
            // amount
            $table->decimal('amount', 10, 2)->nullable()->default(20000.00)->comment('Amount');
            // currency
            $table->string('currency')->nullable()->default('NGN')->comment('Currency in Naira');
            // requirements
            $table->text('requirements')->nullable();
            // ->default('Completion of the Spatial and Data Science Society of Nigeria training program, passing the certification exam, and adherence to the code of conduct.');
            // benefits of the certification
            $table->text('benefits')->nullable();
            // ->default('Access to exclusive resources, networking opportunities, and professional development workshops.');
            // $table->string('certification_code')->nullable()->default('CSDSN-2025');
            // $table->date('issue_date')->nullable();
            // $table->date('expiry_date')->nullable();
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
        Schema::dropIfExists('certifications');
    }
};
